<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;

class ExperienceController extends Controller
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

        $experiencesHeader = $contents
            ->get('experiences_header', collect())
            ->first();

        $experiences = $contents
            ->get('experiences', collect());

        return view('experience', compact(
            'contents',
            'site',
            'experiencesHeader',
            'experiences'
        ));
    }
}