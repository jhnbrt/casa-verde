<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;

class WellnessController extends Controller
{
    public function index()
    {
        $contents = HomeContent::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        $site = $contents
            ->get('site', collect())
            ->first();

        $wellnessIntro = $contents
            ->get('wellness_intro', collect())
            ->first();

        $wellness = $contents
            ->get('wellness', collect());

        $wellnessQuote = $contents
            ->get('wellness_quote', collect())
            ->first();

        return view('wellness', compact(
            'contents',
            'site',
            'wellnessIntro',
            'wellness',
            'wellnessQuote'
        ));
    }
}