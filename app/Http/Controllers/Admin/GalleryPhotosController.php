<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GalleryController;
use App\Models\HomeContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * One screen to see and change every photo on the Gallery page: the overview tiles and the
 * photos of each category. It edits the same entries as Site content > Gallery.
 */
class GalleryPhotosController extends Controller
{
    /** The booking banner at the bottom of the Gallery page. */
    private const BANNER = 'gallery_cta_icon';

    /** Category key => name, in the order they appear on the Gallery page. */
    private const CATEGORIES = [
        'resort' => 'The Resort',
        'villas' => 'Villas',
        'dining' => 'Dining',
        'wellness' => 'Wellness',
        'experiences' => 'Experiences',
        'sunsets' => 'Sunsets',
    ];

    public function index(): View
    {
        $this->loadMissingPhotos();

        $headings = HomeContent::where('section', 'gallery_headings')->orderBy('sort_order')->get()->keyBy('sort_order');

        $sections = [];
        $position = 0;

        foreach (self::CATEGORIES as $key => $name) {
            $position++;

            $sections[$key] = [
                'key' => $key,
                'title' => $headings->get($position)?->title ?: $name,
                'labelled' => $key === 'experiences',
                'photos' => HomeContent::where('section', "gallery_{$key}")->orderBy('sort_order')->orderBy('id')->get(),
            ];
        }

        $banner = HomeContent::where('section', self::BANNER)->orderBy('id')->first()
            ?? HomeContent::create([
                'section' => self::BANNER,
                'title' => 'Ready to experience it in person?',
                'icon' => 'leaf',
                'sort_order' => 1,
                'active' => true,
            ]);

        return view('admin.gallery.index', [
            'overview' => HomeContent::where('section', 'gallery_overview')->orderBy('sort_order')->get(),
            'sections' => $sections,
            'banner' => $banner,
        ]);
    }

    /**
     * Replace the photo and/or change the caption of one photo or overview tile.
     */
    public function update(Request $request, HomeContent $content): RedirectResponse
    {
        $this->guard($content);

        $data = $request->validate([
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:8192'],
            'icon_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,gif,svg', 'max:2048'],
            'title' => ['nullable', 'string', 'max:160'],
            'subtitle' => ['nullable', 'string', 'max:60'],
            'button_text' => ['nullable', 'string', 'max:60'],
        ]);

        $content->title = $data['title'] ?? $content->title;

        if ($content->section === 'gallery_experiences') {
            $content->subtitle = $data['subtitle'] ?? $content->subtitle;
        }

        if ($content->section === self::BANNER) {
            $content->button_text = $data['button_text'] ?? $content->button_text;
        }

        // A tile or photo can be replaced; the banner has no photo, only its icon.
        if ($request->hasFile('image') && $content->section !== self::BANNER) {
            $content->image = $this->storePhoto($request->file('image'));
        }

        // The small icon: the label icon under an Experiences photo, or the banner icon.
        if ($request->hasFile('icon_image') && in_array($content->section, ['gallery_experiences', self::BANNER], true)) {
            $content->icon = $this->storeIcon($request->file('icon_image'));
        }

        $content->save();

        return back()->with('status', 'Saved.');
    }

    /**
     * Add a photo to the end of a category.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category' => ['required', 'in:'.implode(',', array_keys(self::CATEGORIES))],
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:8192'],
            'title' => ['nullable', 'string', 'max:160'],
            'subtitle' => ['nullable', 'string', 'max:60'],
        ]);

        $section = 'gallery_'.$data['category'];
        $labelled = $data['category'] === 'experiences' && filled($data['subtitle'] ?? null);

        HomeContent::create([
            'section' => $section,
            'title' => $data['title'] ?: self::CATEGORIES[$data['category']],
            'subtitle' => $labelled ? $data['subtitle'] : null,
            'icon' => $labelled ? 'island' : null,
            'image' => $this->storePhoto($request->file('image')),
            'sort_order' => ((int) HomeContent::where('section', $section)->max('sort_order')) + 1,
            'active' => true,
        ]);

        return back()->with('status', 'Photo added.');
    }

    /**
     * Take a photo out of its category (the picture file itself is kept).
     */
    public function destroy(HomeContent $content): RedirectResponse
    {
        $this->guard($content, allowOverview: false);

        $content->delete();

        return back()->with('status', 'Photo removed from the gallery.');
    }

    /**
     * Move a photo one place up or down.
     */
    public function move(Request $request, HomeContent $content): RedirectResponse
    {
        $this->guard($content, allowOverview: false);

        $direction = $request->input('direction') === 'up' ? -1 : 1;

        DB::transaction(function () use ($content, $direction) {
            $rows = HomeContent::where('section', $content->section)->orderBy('sort_order')->orderBy('id')->get()->values();
            $index = $rows->search(fn (HomeContent $row) => $row->id === $content->id);

            if ($index === false) {
                return;
            }

            $swap = $index + $direction;

            if ($swap < 0 || $swap >= $rows->count()) {
                return;
            }

            $order = $rows->all();
            [$order[$index], $order[$swap]] = [$order[$swap], $order[$index]];

            foreach ($order as $position => $row) {
                if ((int) $row->sort_order !== $position + 1) {
                    $row->update(['sort_order' => $position + 1]);
                }
            }
        });

        return back()->with('status', 'Order changed.');
    }

    /**
     * Only Gallery photo entries can be changed here.
     */
    private function guard(HomeContent $content, bool $allowOverview = true): void
    {
        $allowed = array_map(fn (string $key) => "gallery_{$key}", array_keys(self::CATEGORIES));

        if ($allowOverview) {
            $allowed[] = 'gallery_overview';
            $allowed[] = self::BANNER;
        }

        abort_unless(in_array($content->section, $allowed, true), 404);
    }

    /**
     * The first time (or after every photo of a category was removed) show the photos the Gallery
     * page is using right now, so each one can be replaced instead of starting from an empty list.
     */
    private function loadMissingPhotos(): void
    {
        try {
            [$overview, $sections] = app(GalleryController::class)->defaultLayout();

            if (! HomeContent::where('section', 'gallery_overview')->exists()) {
                foreach ($overview as $index => $tile) {
                    HomeContent::create([
                        'section' => 'gallery_overview',
                        'title' => $tile['label'],
                        'image' => $tile['img'],
                        'sort_order' => $index + 1,
                        'active' => true,
                    ]);
                }
            }

            foreach ($sections as $key => $section) {
                if (HomeContent::where('section', "gallery_{$key}")->exists()) {
                    continue;
                }

                foreach ($section['items'] as $index => $item) {
                    HomeContent::create([
                        'section' => "gallery_{$key}",
                        'title' => $item['alt'],
                        'subtitle' => $item['label'] ?? null,
                        'icon' => $item['icon'] ?? null,
                        'image' => $item['img'],
                        'sort_order' => $index + 1,
                        'active' => true,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Save an uploaded icon. An SVG must be a plain static drawing (no scripts, links or
     * external files), the same rule used for icons everywhere else in the admin.
     */
    private function storeIcon(UploadedFile $file): string
    {
        if (strtolower($file->getClientOriginalExtension()) === 'svg') {
            $svg = (string) file_get_contents($file->getRealPath());

            if (! preg_match('/<svg\\b/i', $svg)
                || preg_match('/<\\s*(script|foreignObject|iframe|object|embed|use|image|animate|set)\\b|\\bon[a-z]+\\s*=|(?:href|src)\\s*=|javascript:|data:|<!DOCTYPE|<!ENTITY|<\\?xml-stylesheet/i', $svg)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'icon_image' => 'Upload a static SVG icon without scripts, links, external resources or animations.',
                ]);
            }
        }

        return $this->storePhoto($file);
    }

    /**
     * Save an uploaded photo in public/images, named like every other upload on the site.
     */
    private function storePhoto(UploadedFile $file): string
    {
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'photo';
        $extension = strtolower($file->getClientOriginalExtension()) === 'svg' ? 'svg' : ($file->guessExtension() ?: 'jpg');
        $filename = now()->format('Ymd_His').'_'.Str::uuid().'_'.$name.'.'.$extension;

        $file->move(public_path('images'), $filename);

        return 'images/'.$filename;
    }
}
