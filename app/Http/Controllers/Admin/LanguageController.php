<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Languages;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LanguageController extends Controller
{
    /** Which languages the website offers, and how a first-time visitor's language is picked. */
    public function edit(): View
    {
        return view('admin.languages.edit', [
            'settings' => Languages::settings(),
            'supported' => config('languages.supported'),
            'countries' => config('languages.countries'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'languages' => ['nullable', 'array'],
            'languages.*' => ['string', Rule::in(array_keys(config('languages.supported')))],
        ]);

        Languages::save(
            $data['languages'] ?? [],
            $request->boolean('show'),
            $request->boolean('auto'),
            $request->boolean('ip_lookup'),
        );

        return back()->with('status', 'Language settings saved.');
    }
}
