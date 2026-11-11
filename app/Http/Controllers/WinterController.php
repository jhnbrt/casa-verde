<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;
use App\Support\WinterResidence;
use Illuminate\View\View;

class WinterController extends Controller
{
    public function index(): View
    {
        $winter = WinterResidence::content();

        abort_if($winter === null, 404);

        $contents = HomeContent::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        $site = $contents->get('site', collect())->first();

        return view('winter', compact('contents', 'site', 'winter'));
    }
}
