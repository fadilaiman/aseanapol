@extends('admin.layout')
@section('title', 'Edit Collection')

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

        <form method="POST" action="{{ route('admin.digital-library.collections.update', $collection) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Collection Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $collection->name) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Icon <span class="text-red-500">*</span></label>
                <input type="text" name="icon" value="{{ old('icon', $collection->icon) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                <div class="mt-2 flex items-center gap-2 text-sm text-gray-500">
                    Preview: <span class="material-symbols-outlined" style="font-size:22px;">{{ old('icon', $collection->icon) }}</span>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $collection->sort_order) }}" min="0"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.digital-library.collections.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:text-gray-800">Cancel</a>
                <button type="submit"
                        class="px-5 py-2 text-sm font-semibold text-white rounded-lg"
                        style="background:#0a2540;">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
