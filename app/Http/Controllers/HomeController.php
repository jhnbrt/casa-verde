<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;

class HomeController extends Controller
{
    public function index()
    {
        $contents = HomeContent::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        return view('home', compact('contents'));
    }
}