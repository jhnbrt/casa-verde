<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;

class LongStayController extends Controller
{
    public function index()
    {
        $contents = HomeContent::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        $site = $contents->get('site', collect())->first();

        return view('longstay', compact('contents', 'site'));
    }
}
