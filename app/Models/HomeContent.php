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
        'button_text',
        'button_url',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];

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
     * Every admin group that has entries, keyed by group key, with the counts,
     * sections and cover photo the dashboard and content list need.
     */
    public static function pageGroups(): Collection
    {
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
