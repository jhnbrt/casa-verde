<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContentController extends Controller
{
    /**
     * List content entries, optionally filtered by section.
     */
    public function index(Request $request): View
    {
        $section = $request->query('section');

        $sections = HomeContent::query()
            ->select('section')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        $items = HomeContent::query()
            ->when($section, fn ($query) => $query->where('section', $section))
            ->orderBy('section')
            ->orderBy('sort_order')
            ->get();

        return view('admin.content.index', [
            'items' => $items,
            'sections' => $sections,
            'currentSection' => $section,
        ]);
    }

    /**
     * Show the form for creating a new entry.
     */
    public function create(Request $request): View
    {
        $item = new HomeContent([
            'section' => $request->query('section'),
            'active' => true,
        ]);

        return view('admin.content.form', [
            'item' => $item,
            'sections' => $this->existingSections(),
        ]);
    }

    /**
     * Persist a newly created entry.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request);
        }

        $item = HomeContent::create($data);

        return redirect()
            ->route('admin.content.edit', $item)
            ->with('status', 'Entry created successfully.');
    }

    /**
     * Show the form for editing an entry.
     */
    public function edit(HomeContent $content): View
    {
        return view('admin.content.form', [
            'item' => $content,
            'sections' => $this->existingSections(),
        ]);
    }

    /**
     * Update an existing entry.
     */
    public function update(Request $request, HomeContent $content): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request);
        }

        $content->update($data);

        return redirect()
            ->route('admin.content.edit', $content)
            ->with('status', 'Entry updated successfully.');
    }

    /**
     * Delete an entry.
     */
    public function destroy(HomeContent $content): RedirectResponse
    {
        $section = $content->section;
        $content->delete();

        return redirect()
            ->route('admin.content.index', ['section' => $section])
            ->with('status', 'Entry deleted.');
    }

    /**
     * Validate the shared create/update payload.
     */
    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'section' => ['required', 'string', 'max:100'],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'price' => ['nullable', 'numeric'],
            'features' => ['nullable', 'string'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        // "features" is submitted as one item per line in a textarea and
        // stored as a JSON array on the model.
        $data['features'] = $request->filled('features')
            ? collect(preg_split('/\r\n|\r|\n/', $request->input('features')))
                ->map(fn ($line) => trim($line))
                ->filter()
                ->values()
                ->all()
            : null;

        $data['active'] = $request->boolean('active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        unset($data['image']);

        return $data;
    }

    /**
     * Move the uploaded image into public/images and return its stored path,
     * matching the "images/filename.ext" convention already used across the site.
     */
    protected function storeImage(Request $request): string
    {
        $file = $request->file('image');
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename = now()->format('Ymd_His').'_'.$name.'.'.$file->getClientOriginalExtension();

        $file->move(public_path('images'), $filename);

        return 'images/'.$filename;
    }

    /**
     * All distinct section names currently in use, for the section datalist.
     */
    protected function existingSections(): \Illuminate\Support\Collection
    {
        return HomeContent::query()
            ->select('section')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');
    }
}
