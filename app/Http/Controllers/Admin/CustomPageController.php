<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomPage;
use App\Support\HtmlSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/** Small generic pages (privacy policy, terms, ...) reachable at /{locale}/page/{slug}. */
class CustomPageController extends Controller
{
    public function index()
    {
        return view('admin.custom-pages.index', ['pages' => CustomPage::orderBy('title')->paginate(25)]);
    }

    public function create()
    {
        return view('admin.custom-pages.create', ['page' => new CustomPage(['is_published' => false])]);
    }

    public function store(Request $request)
    {
        CustomPage::create($this->validated($request));
        $this->forgetSitemap();

        return redirect()->route('admin.custom-pages.index')->with('success', 'Page created. Hidden until you publish it.');
    }

    public function edit(CustomPage $customPage)
    {
        return view('admin.custom-pages.edit', ['page' => $customPage]);
    }

    public function update(Request $request, CustomPage $customPage)
    {
        $customPage->update($this->validated($request, $customPage));
        $this->forgetSitemap();

        return redirect()->route('admin.custom-pages.index')->with('success', 'Page updated.');
    }

    public function destroy(CustomPage $customPage)
    {
        $customPage->delete();
        $this->forgetSitemap();

        return redirect()->route('admin.custom-pages.index')->with('success', 'Page deleted. Footer links pointing to it will no longer work.');
    }

    public function toggle(CustomPage $customPage)
    {
        $customPage->update(['is_published' => ! $customPage->is_published]);
        $this->forgetSitemap();

        return back()->with('success', $customPage->is_published ? 'Page is now live.' : 'Page hidden (visitors get a 404).');
    }

    private function validated(Request $request, ?CustomPage $page = null): array
    {
        $slug = Str::slug((string) $request->input('slug')) ?: Str::slug((string) $request->input('title'));
        $request->merge(['slug' => $slug]);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug'  => ['required', 'string', 'max:120', 'regex:/^[a-z0-9][a-z0-9-]*$/', Rule::unique('custom_pages', 'slug')->ignore($page?->id)],
            'body'  => 'nullable|string|max:500000',
        ]);
        $data['body'] = HtmlSanitizer::clean($data['body'] ?? '');
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }

    private function forgetSitemap(): void
    {
        Cache::forget('sitemap.xml.v1.' . md5(request()->getSchemeAndHttpHost()));
    }
}
