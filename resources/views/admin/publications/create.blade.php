@extends('admin.layout')
@section('title', 'Add Publication')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('admin.publications.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-5">
        <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
        Back to Publications
    </a>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.publications.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @include('admin.publications._form')

            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.publications.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg transition-colors">Cancel</a>
                <button type="submit" data-testid="save"
                        class="px-5 py-2 text-sm font-semibold text-white rounded-lg transition-colors"
                        style="background:#0a2540;">Add Publication</button>
            </div>
        </form>
    </div>
</div>
@endsection
