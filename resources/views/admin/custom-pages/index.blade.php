@extends('admin.layout')
@section('title', 'Custom Pages')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <div class="text-sm text-gray-500" data-testid="pages-total">{{ $pages->total() }} page(s)</div>
    <a href="{{ route('admin.custom-pages.create') }}" data-testid="add-page"
       class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-white text-sm font-semibold" style="background:#0a2540;">
        <span class="material-symbols-outlined" style="font-size:18px;">add</span> Add Page
    </a>
</div>

<div class="bg-blue-50 border border-blue-200 text-blue-900 text-sm px-4 py-3 rounded-lg mb-4">
    Pages here are public at <code>/en/page/&lt;slug&gt;</code> once published. To link one from the footer, open
    <a class="underline font-semibold" href="{{ route('admin.cms.edit', 'site.header-footer') }}">Site Content &rarr; Header, Menu &amp; Footer</a>
    and type <code>page:&lt;slug&gt;</code> (e.g. <code>page:privacy-policy</code>) in a footer link's Link field.
    Footer links left empty or "#" are not shown to visitors.
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm" data-testid="pages-table">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Title</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600 hidden md:table-cell">Address</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600 hidden lg:table-cell">Updated</th>
                <th class="text-center px-5 py-3 font-medium text-gray-600">Status</th>
                <th class="text-right px-5 py-3 font-medium text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pages as $p)
            <tr class="hover:bg-gray-50/50">
                <td class="px-5 py-3 font-medium text-gray-900">{{ $p->title }}</td>
                <td class="px-5 py-3 hidden md:table-cell text-gray-500">
                    @if($p->is_published)
                        <a class="text-blue-600 hover:underline" target="_blank" rel="noopener" href="{{ route('custom-page', ['locale' => 'en', 'slug' => $p->slug]) }}">/en/page/{{ $p->slug }}</a>
                    @else
                        /en/page/{{ $p->slug }}
                    @endif
                    <div class="text-xs text-gray-400">footer link: <code>page:{{ $p->slug }}</code></div>
                </td>
                <td class="px-5 py-3 text-gray-500 hidden lg:table-cell whitespace-nowrap">{{ $p->updated_at?->format('d M Y H:i') }}</td>
                <td class="px-5 py-3 text-center">
                    <form method="POST" action="{{ route('admin.custom-pages.toggle', $p) }}">
                        @csrf
                        <button type="submit" data-testid="toggle-{{ $p->id }}" title="Click to {{ $p->is_published ? 'hide' : 'publish' }}"
                                class="text-xs px-3 py-1 rounded-full font-medium {{ $p->is_published ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            {{ $p->is_published ? 'Published' : 'Hidden' }}
                        </button>
                    </form>
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.custom-pages.edit', $p) }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.custom-pages.destroy', $p) }}" onsubmit="return confirm('Delete this page permanently?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm">No custom pages yet. <a href="{{ route('admin.custom-pages.create') }}" class="text-blue-600 hover:underline">Add one</a> (e.g. Privacy Policy, Terms of Use).</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($pages->hasPages())<div class="mt-4">{{ $pages->links() }}</div>@endif
@endsection
