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
     * List content entries, filtered by area of the site (group) and/or section.
     */
    public function index(Request $request): View
    {
        $section = $request->query('section');

        $groups = HomeContent::pageGroups();

        // A ?section= link carries its own group so the tabs stay in sync.
        $currentGroup = $groups->get((string) $request->query('group', ''))
            ?? ($section ? $groups->get(HomeContent::groupKeyFor($section)) : null);

        $items = HomeContent::query()
            ->when($section, fn ($query) => $query->where('section', $section))
            ->when(! $section && $currentGroup, fn ($query) => $query->whereIn(
                'section',
                collect($currentGroup['sections'])->pluck('name')
            ))
            ->orderBy('section')
            ->orderBy('sort_order')
            ->get();

        $blocks = $items
            ->groupBy('section')
            ->map(fn ($entries, $name) => [
                'name' => $name,
                'label' => HomeContent::sectionLabel($name),
                'items' => $entries,
            ])
            // Sections described in config/admin.php follow that order (the order
            // they appear on the site); the rest keep their alphabetical order.
            ->sortBy(fn (array $block): int => ($position = array_search($block['name'], array_keys(config('admin.sections', [])), true)) === false
                ? PHP_INT_MAX
                : $position)
            ->values();

        return view('admin.content.index', [
            'blocks' => $blocks,
            'groups' => $groups,
            'currentGroup' => $currentGroup,
            'currentSection' => $section,
            'totalEntries' => $groups->sum('total'),
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

        // An uploaded icon image is stored in the "icon" field as its path.
        if ($request->hasFile('icon_image')) {
            $data['icon'] = $this->storeImage($request, 'icon_image');
        }

        if (($gallery = $this->gallery($request)) !== null) {
            $data['gallery'] = $gallery;
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

        // An uploaded icon image is stored in the "icon" field as its path.
        if ($request->hasFile('icon_image')) {
            $data['icon'] = $this->storeImage($request, 'icon_image');
        }

        if (($gallery = $this->gallery($request)) !== null) {
            $data['gallery'] = $gallery;
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
            'icon' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric'],
            'features' => ['nullable', 'string'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:5120'],
            'icon_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,gif,svg', 'max:2048'],
            'gallery' => ['nullable', 'array', 'max:30'],
            'gallery.*' => ['string', 'max:255'],
            'gallery_new' => ['nullable', 'array', 'max:20'],
            'gallery_new.*' => ['file', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]);

        // Titles and text can contain deliberate line breaks (the site renders
        // them with nl2br). Textareas submit Windows line endings, so store
        // plain "\n" like the rest of the content.
        foreach (['title', 'subtitle', 'description'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = str_replace(["\r\n", "\r"], "\n", $data[$field]);
            }
        }

        // "features" is submitted as one item per line in a textarea; the model
        // stores it as a JSON array. Only touch it when the field was actually
        // submitted, so a partial save can never wipe it.
        if ($request->has('features')) {
            $data['features'] = collect(preg_split('/\r\n|\r|\n/', (string) $request->input('features')))
                ->map(fn ($line) => trim($line))
                ->filter()
                ->values()
                ->all();
        } else {
            unset($data['features']);
        }

        // Same rule for visibility and ordering: never reset them silently.
        if ($request->has('active')) {
            $data['active'] = $request->boolean('active');
        }

        if ($request->filled('sort_order')) {
            $data['sort_order'] = (int) $request->input('sort_order');
        } else {
            unset($data['sort_order']);
        }

        unset($data['image'], $data['icon_image'], $data['gallery'], $data['gallery_new']);

        return $data;
    }

    /**
     * Move the uploaded image into public/images and return its stored path,
     * matching the "images/filename.ext" convention already used across the site.
     */
    protected function storeImage(Request $request, string $field = 'image'): string
    {
        return $this->storeFile($request->file($field), $field);
    }

    /**
     * The photos of an entry's gallery, in order: the ones kept from before (in the order the
     * form lists them) followed by newly uploaded ones. Null when the form had no gallery box,
     * so saving any other entry can never touch a gallery.
     *
     * @return array<int, string>|null
     */
    protected function gallery(Request $request): ?array
    {
        if (! $request->boolean('gallery_present')
            || ! \Illuminate\Support\Facades\Schema::hasColumn('home_contents', 'gallery')) {
            return null;
        }

        $kept = collect((array) $request->input('gallery', []))
            ->filter(fn ($path) => is_string($path)
                && ! str_contains($path, '..')
                && preg_match('#^images/[A-Za-z0-9._/-]+\.(jpe?g|png|webp|gif|svg)$#i', $path)
                && file_exists(public_path($path)))
            ->unique()
            ->values()
            ->all();

        $added = [];

        foreach ((array) $request->file('gallery_new', []) as $file) {
            if ($file && $file->isValid()) {
                $added[] = $this->storeFile($file, 'gallery_new');
            }
        }

        return array_slice(array_merge($kept, $added), 0, 30);
    }

    protected function storeFile(\Illuminate\Http\UploadedFile $file, string $field): string
    {
        if (strtolower($file->getClientOriginalExtension()) === 'svg') {
            $svg = file_get_contents($file->getRealPath());
            if (! preg_match('/<svg\b/i', $svg) || preg_match('/<\s*(script|foreignObject|iframe|object|embed|use|image|animate|set)\b|\bon[a-z]+\s*=|(?:href|src)\s*=|javascript:|data:|<!DOCTYPE|<!ENTITY|<\?xml-stylesheet/i', $svg)) {
                throw \Illuminate\Validation\ValidationException::withMessages([$field => 'Upload a static SVG icon without scripts, links, external resources or animations.']);
            }
        }
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename = now()->format('Ymd_His').'_'.Str::uuid().'_'.$name.'.'.$file->getClientOriginalExtension();

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
