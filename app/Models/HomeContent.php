<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class HomeContent extends Model
{
    protected $fillable = [
        'section',
        'title',
        'subtitle',
        'description',
        'image',
        'icon',
        'price',
        'features',
        'gallery',
        'button_text',
        'button_url',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
        'gallery' => 'array',
    ];

    /**
     * Entries of a page section: what is saved in the admin, or else the starting
     * content from config/pagecontent.php (as unsaved entries, so views read both
     * the same way).
     *
     * @param  Collection<string, Collection<int, static>>  $contents  active rows grouped by section
     * @return Collection<int, static>
     */
    public static function pageRows(Collection $contents, string $section): Collection
    {
        $rows = $contents->get($section, collect());

        if ($rows->isNotEmpty()) {
            return $rows->values();
        }

        return collect(config("pagecontent.{$section}", []))
            ->map(fn (array $attributes) => new static($attributes + ['active' => true]))
            ->values();
    }

    /**
     * First entry of a page section (see pageRows()).
     *
     * @param  Collection<string, Collection<int, static>>  $contents
     */
    public static function pageRow(Collection $contents, string $section): ?static
    {
        return static::pageRows($contents, $section)->first();
    }

    /**
     * Short labels kept one per entry, keyed by position (1, 2, 3...).
     *
     * @param  Collection<string, Collection<int, static>>  $contents
     * @return array<int, string>
     */
    public static function pageLabels(Collection $contents, string $section): array
    {
        return static::pageRows($contents, $section)
            ->values()
            ->mapWithKeys(fn (self $row, int $index) => [(int) ($row->sort_order ?: $index + 1) => (string) $row->title])
            ->all();
    }

    /**
     * One entry of a page section by its position (sort_order), falling back to the
     * starting content from config/pagecontent.php when it is not in the admin yet.
     *
     * @param  Collection<string, Collection<int, static>>  $contents
     */
    public static function pageItem(Collection $contents, string $section, int $position): ?static
    {
        $row = $contents->get($section, collect())->firstWhere('sort_order', $position);

        if ($row) {
            return $row;
        }

        $default = config("pagecontent.{$section}.".($position - 1));

        return $default ? new static($default + ['active' => true]) : null;
    }

    /**
     * The resort logo: the picture set on the Site-wide entry in the admin, or the
     * built-in logo when none is set or the file is missing. Used by the header, the
     * round badge inside every button, the footer and the admin screens.
     */
    public static function siteLogo(): string
    {
        static $logo = null;

        if ($logo !== null) {
            return $logo;
        }

        try {
            $path = trim((string) static::where('section', 'site')->where('active', true)->orderBy('sort_order')->value('image'));
        } catch (\Throwable) {
            $path = '';
        }

        return $logo = ($path !== '' && file_exists(public_path($path))) ? $path : 'images/logo.png';
    }

    /**
     * An icon the admin has replaced with an uploaded image, as opposed to a built-in
     * placeholder or an icon name. Returns null while the original design should show.
     */
    public static function customIconImage(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === ''
            || ! preg_match('/\.(png|jpe?g|webp|gif|svg)$/i', $value)
            || str_starts_with($value, 'images/icons/placeholders/')
            || ! file_exists(public_path($value))) {
            return null;
        }

        return $value;
    }

    /**
     * A shared site icon (see the "Site icons" section in the admin): an uploaded
     * image path, an emoji, or an icon name.
     */
    public static function siteIcon(string $key): string
    {
        static $rows = null;

        $position = ['arrow' => 1, 'leaf' => 2, 'pin' => 3, 'lotus' => 4, 'villa' => 5][$key] ?? 0;

        if ($rows === null) {
            try {
                $rows = static::where('section', 'site_icons')->where('active', true)->pluck('icon', 'sort_order')->all();
            } catch (\Throwable) {
                $rows = [];
            }
        }

        return trim((string) ($rows[$position] ?? config('pagecontent.site_icons.'.($position - 1).'.icon', $key)));
    }

    /**
     * The site-wide "line design": style, colour and default ornament of the
     * divider lines. Edited in the admin under Site-wide > Line design.
     *
     * @return array{ornament: string, style: string, color: ?string}
     */
    public static function divider(): array
    {
        static $design = null;

        if ($design !== null) {
            return $design;
        }

        try {
            $row = static::where('section', 'site_divider')->where('active', true)->orderBy('sort_order')->first();
        } catch (\Throwable) {
            $row = null;
        }

        $style = strtolower(trim((string) $row?->subtitle));
        $color = trim((string) $row?->title);

        return $design = [
            'ornament' => trim((string) $row?->icon) !== '' ? trim((string) $row->icon) : 'lotus',
            'style' => in_array($style, ['solid', 'dashed', 'dotted', 'double', 'fade'], true) ? $style : 'solid',
            'color' => preg_match('/^#[0-9a-f]{3,8}$/i', $color) ? $color : null,
        ];
    }

    /**
     * Human-friendly name for a section slug: "long_stay_options" => "Long stay options".
     */
    public static function sectionLabel(string $section): string
    {
        return config("admin.sections.{$section}.title")
            ?? Str::of($section)->replace('_', ' ')->ucfirst()->toString();
    }

    /**
     * Admin form settings for a section (friendly labels, help, hidden fields).
     * See "sections" in config/admin.php. Empty for sections without settings.
     *
     * @return array<string, mixed>
     */
    public static function sectionMeta(?string $section): array
    {
        return $section ? (array) config("admin.sections.{$section}", []) : [];
    }

    /**
     * Key of the admin group (see config/admin.php) a section belongs to, or "other".
     */
    public static function groupKeyFor(string $section): string
    {
        foreach (config('admin.content_groups', []) as $key => $group) {
            foreach ($group['match'] as $prefix) {
                if ($section === $prefix || str_starts_with($section, $prefix.'_')) {
                    return $key;
                }
            }
        }

        return 'other';
    }

    /**
     * Load the starting content of the Dining, Experiences, Wellness, Villas, Long stay,
     * Home symbols, site icons and villa highlight entries into the admin the first time
     * it is opened, so they can be edited without running any command. Existing entries
     * are never overwritten, and nothing happens once the content is there.
     */
    public static function ensurePageDefaults(): void
    {
        static $checked = false;

        if ($checked) {
            return;
        }

        $checked = true;

        try {
            // The Reviews page content loads on its own, independent of the checks below.
            if (! static::where('section', 'reviews_icons')->exists()) {
                app(\Database\Seeders\ReviewContentSeeder::class)->run();
            }

            // The redesigned Long Stay page adds entries (box items, side photos); load them once.
            if (! static::where('section', 'long_stay_gallery')->exists()) {
                app(\Database\Seeders\LongStayContentSeeder::class)->run();
            }

            // Villa photo galleries: give every villa its starting photos once.
            static::fillVillaGalleries();

            if (static::where('section', 'dining_page_hero')->exists()
                && static::where('section', 'site_icons')->exists()
                && static::where('section', 'long_stay_hero')->exists()) {
                return;
            }

            app(\Database\Seeders\LongStayContentSeeder::class)->run();
            app(\Database\Seeders\PageContentDefaultsSeeder::class)->run();

            // Icon names (lotus, rings...) become editable placeholder images.
            foreach (glob(public_path('images/icons/placeholders/*.svg')) ?: [] as $file) {
                $name = pathinfo($file, PATHINFO_FILENAME);

                static::where('icon', $name)->update(['icon' => 'images/icons/placeholders/'.$name.'.svg']);
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Give each villa the photos that open in its gallery when its picture is clicked on
     * the Villas page: its card photo, wide photo and gallery photos. Villas that already
     * have a gallery are left alone. Photo entries from the earlier "Villa card: Photos"
     * list are moved into the villa and removed.
     */
    public static function fillVillaGalleries(): void
    {
        if (! \Illuminate\Support\Facades\Schema::hasColumn('home_contents', 'gallery')) {
            return;
        }

        $legacy = static::where('section', 'villas_photos')
            ->orderBy('sort_order')
            ->get()
            ->groupBy(fn (self $row) => Str::slug((string) $row->title));

        foreach (static::where('section', 'villas')->get() as $villa) {
            if (! empty($villa->gallery)) {
                continue;
            }

            $slug = Str::slug((string) $villa->title);

            $photos = $legacy->has($slug)
                ? $legacy->get($slug)->pluck('image')->all()
                : array_merge([$villa->image, config("villas.{$slug}.hero")], (array) config("villas.{$slug}.gallery", []));

            $photos = array_values(array_unique(array_filter(
                $photos,
                fn ($path) => filled($path) && file_exists(public_path($path)),
            )));

            if ($photos !== []) {
                $villa->update(['gallery' => $photos]);
            }
        }

        static::where('section', 'villas_photos')->delete();
    }

    /**
     * Every admin group that has entries, keyed by group key, with the counts,
     * sections and cover photo the dashboard and content list need.
     */
    public static function pageGroups(): Collection
    {
        static::ensurePageDefaults();

        $labels = collect(config('admin.content_groups', []))
            ->map(fn (array $group) => $group['label'])
            ->put('other', 'Other');

        $rowsByGroup = static::query()
            ->orderBy('section')
            ->orderBy('sort_order')
            ->get(['section', 'image', 'active'])
            ->groupBy(fn (self $row) => static::groupKeyFor($row->section));

        return $labels
            ->filter(fn (string $label, string $key) => $rowsByGroup->has($key))
            ->map(function (string $label, string $key) use ($rowsByGroup) {
                $rows = $rowsByGroup->get($key);

                return [
                    'key' => $key,
                    'label' => $label,
                    'url' => config("admin.content_groups.{$key}.url"),
                    'total' => $rows->count(),
                    'hidden_total' => $rows->where('active', false)->count(),
                    'sections' => $rows->pluck('section')->unique()->values()
                        ->map(fn (string $name) => ['name' => $name, 'label' => static::sectionLabel($name)])
                        ->all(),
                    // Logos make poor cover photos, so skip them.
                    'cover' => $rows->pluck('image')
                        ->first(fn ($image) => filled($image) && ! str_contains($image, 'logo')),
                ];
            });
    }

    /**
     * Title on one line, for lists. Falls back so a row is never blank.
     */
    protected function displayTitle(): Attribute
    {
        return Attribute::get(function () {
            $title = Str::of((string) $this->title)->squish()->toString();

            return $title !== '' ? $title : 'Untitled entry';
        });
    }

    /**
     * Title as safe HTML with its line breaks, for the big display headings.
     *
     * Headings such as "Restore. Renew. Rebalance." are designed to sit on
     * separate lines. If an editor types them on a single line (or the line
     * breaks were lost), each sentence is still placed on its own line so a
     * replaced or newly added entry always matches the design.
     */
    protected function titleHtml(): Attribute
    {
        return Attribute::get(function () {
            $title = str_replace(["\r\n", "\r"], "\n", trim((string) $this->title));

            if (! str_contains($title, "\n")) {
                $title = preg_replace('/(?<=[.!?])\s*(?=\p{Lu})/u', "\n", $title);
            }

            return new HtmlString(nl2br(e($title)));
        });
    }

    /**
     * Short one-line preview of the entry's text, for lists.
     */
    protected function excerpt(): Attribute
    {
        return Attribute::get(fn () => Str::limit(
            Str::of((string) ($this->subtitle ?: $this->description))->squish()->toString(),
            90
        ));
    }

    protected function sectionName(): Attribute
    {
        return Attribute::get(fn () => static::sectionLabel((string) $this->section));
    }

    /**
     * Features are a list of strings stored as JSON.
     *
     * The seeder used to store them as an already-encoded JSON string, which
     * the old 'array' cast encoded a second time. Reading therefore returned a
     * string instead of an array, the admin form showed an empty box, and
     * saving the form (even just to change a photo) erased the list.
     *
     * This accessor understands both the old and the correct format and always
     * returns an array; the mutator always stores a clean JSON array.
     */
    protected function features(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $data = $value;

                for ($i = 0; $i < 3 && is_string($data); $i++) {
                    $decoded = json_decode($data, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        break;
                    }

                    $data = $decoded;
                }

                return is_array($data) ? array_values($data) : [];
            },
            set: function ($value) {
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    $value = is_array($decoded) ? $decoded : [];
                }

                $value = is_array($value) ? array_values(array_filter($value, 'filled')) : [];

                return $value === [] ? null : json_encode($value, JSON_UNESCAPED_UNICODE);
            },
        );
    }
}
