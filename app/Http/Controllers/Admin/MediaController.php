<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\MediaLibrary;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaController extends Controller
{
    private const TYPES = ['photo' => 'Photos', 'icon' => 'Icons', 'symbol' => 'Symbols', 'logo' => 'Logos'];

    private const STATUSES = [
        'in_use' => 'In use now',
        'default' => 'Starting design only',
        'hidden' => 'Hidden entry only',
        'unused' => 'Not used',
    ];

    public function index(Request $request): View
    {
        $all = MediaLibrary::all();

        $type = $request->query('type');
        $status = $request->query('status');
        $search = trim((string) $request->query('q'));
        $sort = $request->query('sort', 'newest');

        $items = $all
            ->when(array_key_exists((string) $type, self::TYPES), fn ($c) => $c->where('type', $type))
            ->when(array_key_exists((string) $status, self::STATUSES), fn ($c) => $c->where('status', $status))
            ->when($search !== '', fn ($c) => $c->filter(fn (array $f) => str_contains(strtolower($f['path']), strtolower($search))));

        $items = match ($sort) {
            'name' => $items->sortBy(fn (array $f) => strtolower($f['name'])),
            'oldest' => $items->sortBy(fn (array $f) => $f['uploaded']->getTimestamp()),
            default => $items->sortByDesc(fn (array $f) => $f['uploaded']->getTimestamp()),
        };

        return view('admin.media.index', [
            'items' => $items->values(),
            'types' => self::TYPES,
            'statuses' => self::STATUSES,
            'typeCounts' => $all->countBy('type'),
            'statusCounts' => $all->countBy('status'),
            'total' => $all->count(),
            'type' => $type,
            'status' => $status,
            'search' => $search,
            'sort' => $sort,
        ]);
    }
}
