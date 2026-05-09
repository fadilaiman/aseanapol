@extends('admin.layout')
@section('title', 'Add Collection')

@section('content')
<div class="max-w-lg">
    <a href="{{ route('admin.digital-library.collections.index') }}"
       class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-5">
        <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
        Back to Collections
    </a>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-5">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.digital-library.collections.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Collection Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       placeholder="e.g. ASEANAPOL Bulletins & Magazine"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Icon <span class="text-red-500">*</span></label>
                <input type="text" name="icon" value="{{ old('icon', 'folder') }}" required
                       placeholder="Material Symbol name, e.g. menu_book"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                <p class="text-xs text-gray-400 mt-1">
                    Use a <a href="https://fonts.google.com/icons" target="_blank" class="text-blue-600 hover:underline">Material Symbol</a> name.
                    Common: <code class="bg-gray-100 px-1 rounded">folder</code>, <code class="bg-gray-100 px-1 rounded">menu_book</code>, <code class="bg-gray-100 px-1 rounded">gavel</code>, <code class="bg-gray-100 px-1 rounded">library_books</code>
                </p>
                @if(old('icon'))
                    <div class="mt-2 flex items-center gap-2 text-sm text-gray-500">
                        Preview: <span class="material-symbols-outlined" style="font-size:22px;">{{ old('icon') }}</span>
                    </div>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.digital-library.collections.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:text-gray-800">Cancel</a>
                <button type="submit"
                        class="px-5 py-2 text-sm font-semibold text-white rounded-lg"
                        style="background:#0a2540;">Save Collection</button>
            </div>
        </form>
    </div>
</div>
@endsection
