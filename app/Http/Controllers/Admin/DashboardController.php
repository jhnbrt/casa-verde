<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeContent;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $groups = HomeContent::pageGroups();

        return view('admin.dashboard', [
            'groups' => $groups,
            'totalEntries' => $groups->sum('total'),
            'totalHidden' => $groups->sum('hidden_total'),
            'recent' => HomeContent::query()->latest('updated_at')->limit(6)->get(),
        ]);
    }
}
