<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;

class VillaController extends Controller
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

        $villasHeader = $contents
            ->get('villas_header', collect())
            ->first();

        $villas = $contents
            ->get('villas', collect());

        return view('villa', compact(
            'contents',
            'site',
            'villasHeader',
            'villas'
        ));
    }
}