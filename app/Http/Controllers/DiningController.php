<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;

class DiningController extends Controller
{
    public function index()
    {
        $contents = HomeContent::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        $site = $contents->get('site', collect())->first();

        return view('dining', compact('contents', 'site'));
    }
}