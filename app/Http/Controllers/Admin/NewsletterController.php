<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsletterController extends Controller
{
    public function index()
    {
        $newsletters = Newsletter::orderBy('sort_order')->orderBy('issue_date', 'desc')->paginate(15);

        return view('admin.newsletter.index', compact('newsletters'));
    }

    public function create()
    {
        return view('admin.newsletter.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'edition'    => 'required|string|max:50',
            'issue_date' => 'required|date',
            'topics'     => 'nullable|string',
            'file'       => 'nullable|file|mimes:pdf|max:20480',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['topics'] = $this->parseTopics($request->input('topics'));
        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('newsletters', 'public');
        }

        unset($data['file']);
        Newsletter::create($data);

        return redirect()->route('admin.newsletters.index')->with('success', 'Newsletter added successfully.');
    }

    public function edit(Newsletter $newsletter)
    {
        return view('admin.newsletter.edit', compact('newsletter'));
    }

    public function update(Request $request, Newsletter $newsletter)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'edition'    => 'required|string|max:50',
            'issue_date' => 'required|date',
            'topics'     => 'nullable|string',
            'file'       => 'nullable|file|mimes:pdf|max:20480',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['topics'] = $this->parseTopics($request->input('topics'));
        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('file')) {
            if ($newsletter->file_path) {
                Storage::disk('public')->delete($newsletter->file_path);
            }
            $data['file_path'] = $request->file('file')->store('newsletters', 'public');
        }

        unset($data['file']);
        $newsletter->update($data);

        return redirect()->route('admin.newsletters.index')->with('success', 'Newsletter updated successfully.');
    }

    public function destroy(Newsletter $newsletter)
    {
        if ($newsletter->file_path) {
            Storage::disk('public')->delete($newsletter->file_path);
        }

        $newsletter->delete();

        return redirect()->route('admin.newsletters.index')->with('success', 'Newsletter removed.');
    }

    public function toggle(Newsletter $newsletter)
    {
        $newsletter->update(['is_published' => !$newsletter->is_published]);
        $status = $newsletter->is_published ? 'published' : 'unpublished';

        return back()->with('success', "Newsletter {$status}.");
    }

    private function parseTopics(?string $raw): array
    {
        if (!$raw) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode("\n", $raw))));
    }
}
