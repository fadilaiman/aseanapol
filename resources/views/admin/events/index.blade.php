@extends('admin.layout')
@section('title', 'Events & Training')

@php
    $kindBadge = [
        'calendar'   => 'bg-blue-50 text-blue-700',
        'conference' => 'bg-indigo-50 text-indigo-700',
        'training'   => 'bg-teal-50 text-teal-700',
    ];
    $inputCls = 'border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 bg-white';
    $hasFilters = array_filter($filters, fn ($v) => $v !== '' && $v !== null);
@endphp

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <div class="text-sm text-gray-500" data-testid="events-total">{{ $events->total() }} item(s)</div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.cms.index') }}" class="px-3 py-2 rounded-lg text-sm text-gray-600 border border-gray-300 hover:bg-gray-50">
            Edit page text
        </a>
        <a href="{{ route('admin.events.create') }}" data-testid="add-event"
           class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-white text-sm font-semibold transition-colors" style="background:#0a2540;">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>
            Add Event
        </a>
    </div>
</div>

<form method="GET" action="{{ route('admin.events.index') }}" class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex flex-wrap items-end gap-3" data-testid="filters">
    <div class="flex-1 min-w-[200px]">
        <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
        <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Title, city, country…" class="w-full {{ $inputCls }}">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Kind</label>
        <select name="kind" class="{{ $inputCls }}">
            <option value="">All kinds</option>
            @foreach(\App\Models\Event::KINDS as $k => $label)
                <option value="{{ $k }}" @selected($filters['kind'] === $k)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Year</label>
        <select name="year" class="{{ $inputCls }}">
            <option value="">All years</option>
            @foreach($years as $y)
                <option value="{{ $y }}" @selected((string) $filters['year'] === (string) $y)>{{ $y }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
        <select name="type" class="{{ $inputCls }}">
            <option value="">All types</option>
            @foreach(\App\Models\Event::TYPES as $t)
                <option value="{{ $t }}" @selected($filters['type'] === $t)>{{ $t }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Visibility</label>
        <select name="status" class="{{ $inputCls }}">
            <option value="">Any</option>
            <option value="published" @selected($filters['status'] === 'published')>Visible</option>
            <option value="hidden" @selected($filters['status'] === 'hidden')>Hidden</option>
        </select>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white" style="background:#0a2540;">Filter</button>
        @if($hasFilters)
            <a href="{{ route('admin.events.index') }}" class="px-4 py-2 rounded-lg text-sm text-gray-600 border border-gray-300 hover:bg-gray-50">Reset</a>
        @endif
    </div>
</form>

<div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm" data-testid="events-table">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Title</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600 hidden md:table-cell">Kind</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600 hidden lg:table-cell">Date</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600 hidden lg:table-cell">Location</th>
                <th class="text-center px-5 py-3 font-medium text-gray-600">Status</th>
                <th class="text-right px-5 py-3 font-medium text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($events as $ev)
            <tr class="hover:bg-gray-50/50" data-row-title="{{ $ev->title }}">
                <td class="px-5 py-3">
                    <div class="font-medium text-gray-900 leading-snug">{{ $ev->title }}</div>
                    @if($ev->type)
                        <div class="text-gray-400 text-xs mt-0.5">{{ $ev->type }}</div>
                    @elseif($ev->kind === 'training' && $ev->category)
                        <div class="text-gray-400 text-xs mt-0.5">{{ $ev->category }}</div>
                    @endif
                </td>
                <td class="px-5 py-3 hidden md:table-cell">
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium whitespace-nowrap {{ $kindBadge[$ev->kind] ?? 'bg-gray-100 text-gray-600' }}">{{ \App\Models\Event::KINDS[$ev->kind] ?? $ev->kind }}</span>
                </td>
                <td class="px-5 py-3 text-gray-500 hidden lg:table-cell whitespace-nowrap">
                    @if($ev->kind === 'conference')
                        {{ $ev->dates_label ?: $ev->conf_year }}
                    @elseif($ev->start_date)
                        {{ $ev->start_date->format('d M Y') }}@if($ev->end_date && ! $ev->end_date->isSameDay($ev->start_date)) – {{ $ev->end_date->format('d M Y') }}@endif
                    @else
                        <span class="text-gray-300">—</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-gray-500 hidden lg:table-cell">{{ $ev->location ?: '—' }}</td>
                <td class="px-5 py-3 text-center">
                    <form method="POST" action="{{ route('admin.events.toggle', $ev) }}">
                        @csrf
                        <button type="submit" data-testid="toggle-{{ $ev->id }}" title="Click to {{ $ev->is_published ? 'hide' : 'show' }}"
                                class="text-xs px-3 py-1 rounded-full font-medium transition-colors {{ $ev->is_published ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            {{ $ev->is_published ? 'Visible' : 'Hidden' }}
                        </button>
                    </form>
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.events.edit', $ev) }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.events.destroy', $ev) }}" onsubmit="return confirm('Delete this entry permanently?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-10 text-center text-gray-400 text-sm">
                    @if($hasFilters)
                        Nothing matches these filters. <a href="{{ route('admin.events.index') }}" class="text-blue-600 hover:underline">Reset filters.</a>
                    @else
                        No events yet. <a href="{{ route('admin.events.create') }}" class="text-blue-600 hover:underline">Add one.</a>
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($events->hasPages())
    <div class="mt-4" data-testid="pagination">{{ $events->links() }}</div>
@endif
@endsection
