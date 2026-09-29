@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')

    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
        <p class="mt-1 text-sm text-slate-500">An overview of everything editable on the live site.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total entries</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalEntries }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Sections</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalSections }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Entries with images</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalImages }}</p>
        </div>
    </div>

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-slate-900">Sections</h2>
        <a href="{{ route('admin.content.create') }}"
           class="inline-flex items-center rounded-lg bg-slate-900 text-white text-sm font-medium px-4 py-2 hover:bg-slate-800 transition">
            + New entry
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left font-medium px-5 py-3">Section</th>
                    <th class="text-left font-medium px-5 py-3">Entries</th>
                    <th class="text-left font-medium px-5 py-3">Active</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($sections as $row)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $row->section }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $row->total }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $row->active_total }} / {{ $row->total }}</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.content.index', ['section' => $row->section]) }}"
                               class="text-slate-900 font-medium hover:underline">Manage →</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-6 text-center text-slate-500">No content yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
