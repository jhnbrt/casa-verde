<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeContent;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $sections = HomeContent::query()
            ->selectRaw('section, count(*) as total, sum(case when active then 1 else 0 end) as active_total')
            ->groupBy('section')
            ->orderBy('section')
            ->get();

        $totalEntries = HomeContent::count();
        $totalImages = HomeContent::whereNotNull('image')->where('image', '!=', '')->count();

        return view('admin.dashboard', [
            'sections' => $sections,
            'totalEntries' => $totalEntries,
            'totalSections' => $sections->count(),
            'totalImages' => $totalImages,
        ]);
    }
}
