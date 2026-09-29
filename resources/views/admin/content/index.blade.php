@extends('admin.layout')

@section('title', 'Site Content')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Site Content</h1>
            <p class="mt-1 text-sm text-slate-500">
                Every image and block of text shown on the public site lives here, grouped by section.
            </p>
        </div>
        <a href="{{ route('admin.content.create', $currentSection ? ['section' => $currentSection] : []) }}"
           class="inline-flex items-center justify-center rounded-lg bg-slate-900 text-white text-sm font-medium px-4 py-2 hover:bg-slate-800 transition whitespace-nowrap">
            + New entry
        </a>
    </div>

    {{-- Section filter --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('admin.content.index') }}"
           class="px-3 py-1.5 rounded-full text-xs font-medium border
                  {{ ! $currentSection ? 'bg-slate-900 text-white border-slate-900' : 'text-slate-600 border-slate-300 hover:border-slate-400' }}">
            All
        </a>
        @foreach ($sections as $section)
            <a href="{{ route('admin.content.index', ['section' => $section]) }}"
               class="px-3 py-1.5 rounded-full text-xs font-medium border
                      {{ $currentSection === $section ? 'bg-slate-900 text-white border-slate-900' : 'text-slate-600 border-slate-300 hover:border-slate-400' }}">
                {{ $section }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left font-medium px-5 py-3">Image</th>
                    <th class="text-left font-medium px-5 py-3">Section</th>
                    <th class="text-left font-medium px-5 py-3">Title</th>
                    <th class="text-left font-medium px-5 py-3">Order</th>
                    <th class="text-left font-medium px-5 py-3">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($items as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3">
                            @if ($item->image)
                                <img src="{{ asset($item->image) }}" alt=""
                                     class="h-12 w-16 object-cover rounded-md border border-slate-200">
                            @else
                                <div class="h-12 w-16 rounded-md border border-dashed border-slate-300 flex items-center justify-center text-slate-300 text-xs">
                                    —
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-slate-600">{{ $item->section }}</td>
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $item->title ?: '—' }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $item->sort_order }}</td>
                        <td class="px-5 py-3">
                            @if ($item->active)
                                <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium px-2.5 py-0.5">Active</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-100 text-slate-500 text-xs font-medium px-2.5 py-0.5">Hidden</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.content.edit', $item) }}" class="text-slate-900 font-medium hover:underline mr-4">Edit</a>
                            <form action="{{ route('admin.content.destroy', $item) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this entry? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 font-medium hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-6 text-center text-slate-500">No entries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
