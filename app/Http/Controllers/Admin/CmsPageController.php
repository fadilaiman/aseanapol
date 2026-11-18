<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsBlock;
use App\Support\Cms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CmsPageController extends Controller
{
    public function index()
    {
        $counts = CmsBlock::query()->selectRaw('page_key, count(*) as c')->groupBy('page_key')->pluck('c', 'page_key');

        return view('admin.cms.index', [
            'groups' => Cms::allGroups(),
            'counts' => $counts,
        ]);
    }

    public function edit(string $page)
    {
        return $this->editor($page);
    }

    /**
     * @param Request|null $submitted when set (edit conflict), the editor is pre-filled with the
     *                                submitted values instead of the stored ones.
     */
    private function editor(string $page, ?Request $submitted = null, int $status = 200, ?string $uploadError = null)
    {
        $def = Cms::page($page);
        abort_unless($def, 404);

        $input = $submitted ? (array) $submitted->input('f', []) : [];
        $vis = $submitted ? (array) $submitted->input('vis', []) : [];
        $values = [];
        $hidden = [];
        foreach ($def['sections'] as $sKey => $section) {
            foreach ($section['fields'] as $fKey => $field) {
                $values["$sKey.$fKey"] = ($submitted && is_array($input[$sKey] ?? null) && array_key_exists($fKey, $input[$sKey]))
                    ? Cms::normalize($field, $input[$sKey][$fKey], null)
                    : Cms::raw($page, "$sKey.$fKey");
            }
            $isHidden = $submitted && !empty($section['toggle']) && array_key_exists($sKey, $vis)
                ? ($vis[$sKey] !== '1')
                : !Cms::visible($page, $sKey);
            if ($isHidden) {
                $hidden[] = $sKey;
            }
        }

        return response()->view('admin.cms.edit', [
            'pageKey'  => $page,
            'def'      => $def,
            'group'    => Cms::group(explode('.', $page)[0])['_group'],
            'values'   => $values,
            'hidden'   => $hidden,
            'customized' => Cms::customizedCount($page),
            'liveUrl'  => $this->liveUrl($def),
            'feeds'    => Cms::feeds($def),
            'fingerprint' => Cms::fingerprint($page),
            'conflict' => (bool) $submitted && $uploadError === null,
            'uploadError' => $uploadError,
        ], $status);
    }

    public function update(Request $request, string $page)
    {
        abort_unless(Cms::page($page), 404);

        $request->validate([
            'f'     => 'nullable|array',
            'vis'   => 'nullable|array',
            'files' => 'nullable|array',
        ]);

        // "_end" is the LAST field of the form. PHP silently drops input beyond max_input_vars, and saving a
        // truncated form would DELETE every override that fell off the end -> refuse instead.
        if ($request->input('_end') !== '1') {
            return redirect()->route('admin.cms.edit', $page)->with('error',
                'Nothing was saved: the form arrived incomplete (it probably exceeds the server\'s input limit of ' . (int) ini_get('max_input_vars') . ' fields). Ask the developer to split this page.');
        }

        // A file the server could not accept (e.g. over upload_max_filesize) must never end in a silent "saved":
        // refuse the whole save, name the limit and keep everything the editor typed on screen.
        if ($bad = Cms::failedUpload($request)) {
            return $this->editor($page, $request, 422, Cms::uploadErrorMessage($bad));
        }

        // Someone else saved this page after this form was opened -> show their version warning, keep my edits on screen.
        $fp = (string) $request->input('_fp', '');
        if ($fp !== '' && $fp !== Cms::fingerprint($page)) {
            return $this->editor($page, $request, 409);
        }

        $before = Cms::uploadsOf($page);
        Cms::$storedUploads = [];
        $sent = Cms::uploadCount($request);
        try {
            DB::transaction(function () use ($page, $request, $sent) {
                Cms::save($page, $request);
                // a file the editor sent that did not end up stored (silently dropped) must abort the save, not say "saved"
                if ($sent > count(Cms::$storedUploads)) {
                    throw ValidationException::withMessages(['files' => ($sent - count(Cms::$storedUploads)) . ' of the ' . $sent . ' file(s) you selected could not be stored, so NOTHING was saved. Choose the files again (server limit ' . upload_limit_label(25) . ' per file) and save.']);
                }
            });
        } catch (ValidationException $e) {
            foreach (Cms::$storedUploads as $f) {
                Storage::disk('public')->delete($f);
            }
            throw $e;
        }
        Cms::deleteOrphans($before); // replaced / cleared files no longer referenced anywhere

        return redirect()->route('admin.cms.edit', $page)->with('success', 'Page content saved.');
    }

    public function reset(Request $request, string $page)
    {
        abort_unless(Cms::page($page), 404);

        $before = Cms::uploadsOf($page);
        DB::transaction(fn () => Cms::reset($page, is_string($request->input('section')) ? ($request->input('section') ?: null) : null));
        Cms::deleteOrphans($before);

        return redirect()->route('admin.cms.edit', $page)->with('success', 'Reverted to the original content.');
    }

    private function liveUrl(array $def): ?string
    {
        if (!empty($def['url'])) {
            return url($def['url']);
        }
        if (empty($def['route']) && !empty($def['feeds'])) {
            return Cms::feeds($def)[0]['url'] ?? null; // shared.* pages: "View live page" = first page they feed
        }
        if (!empty($def['route']) && Route::has($def['route'])) {
            $params = array_merge(['locale' => 'en'], $def['route_params'] ?? []);
            try {
                return route($def['route'], $params);
            } catch (\Throwable) {
                return null;
            }
        }

        return null;
    }
}
