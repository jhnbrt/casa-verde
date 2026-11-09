<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;
use App\Support\VillaPhotos;

class HomeController extends Controller
{
    public function index()
    {
        $contents = HomeContent::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        $villaPhotos = VillaPhotos::for($contents->get('villas', collect()));

        return view('home', compact('contents', 'villaPhotos'));
    }
}