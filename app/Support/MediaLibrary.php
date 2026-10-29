<?php

namespace App\Support;

use App\Models\HomeContent;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

/**
 * Lists every picture in public/images (photos, icons, symbols, logos) and works out
 * whether the site is using it right now, so the admin can tell current files from old ones.
 *
 * Status:
 *   in_use  - an active entry in the admin uses it, or the site design itself uses it
 *   hidden  - only entries switched off in the admin use it
 *   default - only the starting content in the code uses it (no admin entry has taken over)
 *   unused  - nothing found that uses it
 */
class MediaLibrary
{
    private const EXTENSIONS = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'];

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function all(): Collection
    {
        $adminUses = self::adminUses();
        $codeUses = self::codeUses();
        $defaultUses = self::defaultUses();

        $files = collect(self::files())->map(function (string $path) use ($adminUses, $codeUses, $defaultUses): array {
            $absolute = public_path($path);
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $size = $extension === 'svg' ? null : @getimagesize($absolute);
            $uploaded = self::uploadedAt($path, $absolute);

            $admin = $adminUses[$path] ?? [];
            $activeAdmin = array_values(array_filter($admin, fn (array $use) => $use['active']));
            $code = $codeUses[$path] ?? [];
            $default = $defaultUses[$path] ?? [];

            $status = match (true) {
                $activeAdmin !== [] || $code !== [] => 'in_use',
                $admin !== [] => 'hidden',
                $default !== [] => 'default',
                default => 'unused',
            };

            return [
                'path' => $path,
                'name' => basename($path),
                'folder' => dirname($path),
                'extension' => $extension,
                'type' => self::typeOf($path, $extension, $size ?: null),
                'width' => $size[0] ?? null,
                'height' => $size[1] ?? null,
                'bytes' => (int) @filesize($absolute),
                'uploaded' => $uploaded,
                'status' => $status,
                'admin_uses' => $admin,
                'code_uses' => $code,
                'default_uses' => $default,
                'group' => self::originalName($path),
                'newer' => false,
                'is_new' => $uploaded->greaterThan(now()->subDays(7)),
            ];
        });

        // A file is an "older version" when another upload has the same original name and is newer.
        $latest = $files->groupBy('group')->map(fn (Collection $group) => $group->max(fn (array $f) => $f['uploaded']->getTimestamp()));

        return $files
            ->map(function (array $file) use ($latest, $files): array {
                $same = $files->where('group', $file['group'])->count();
                $file['versions'] = $same;
                $file['newer'] = $same > 1 && $file['uploaded']->getTimestamp() < $latest[$file['group']];

                return $file;
            })
            ->values();
    }

    /**
     * @return array<int, string> paths such as "images/logo.png"
     */
    private static function files(): array
    {
        $root = public_path('images');

        if (! is_dir($root)) {
            return [];
        }

        return collect(File::allFiles($root))
            ->filter(fn ($file) => in_array(strtolower($file->getExtension()), self::EXTENSIONS, true))
            ->map(fn ($file) => 'images/'.str_replace('\\', '/', $file->getRelativePathname()))
            ->values()
            ->all();
    }

    /**
     * Where the admin entries use each picture: path => [{id, section, title, active, field}].
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    private static function adminUses(): array
    {
        $uses = [];

        try {
            $rows = HomeContent::query()->get(['id', 'section', 'title', 'image', 'icon', 'gallery', 'active']);
        } catch (\Throwable) {
            return [];
        }

        foreach ($rows as $row) {
            $fields = [
                'Photo' => [$row->image],
                'Icon' => self::iconPaths($row->icon),
                'Gallery photo' => (array) $row->gallery,
            ];

            foreach ($fields as $field => $values) {
                foreach ($values as $value) {
                    $path = self::clean($value);

                    if ($path === null) {
                        continue;
                    }

                    $uses[$path][] = [
                        'id' => $row->id,
                        'section' => HomeContent::sectionLabel((string) $row->section),
                        'title' => $row->display_title,
                        'active' => (bool) $row->active,
                        'field' => $field,
                    ];
                }
            }
        }

        return $uses;
    }

    /**
     * Pictures the site design itself names: header, footer, stylesheets, scripts and PHP code.
     *
     * @return array<string, array<int, string>> path => file names
     */
    private static function codeUses(): array
    {
        $uses = [];
        $dirs = [resource_path('views'), resource_path('css'), resource_path('js'), app_path()];

        foreach ($dirs as $dir) {
            if (! is_dir($dir)) {
                continue;
            }

            foreach (File::allFiles($dir) as $file) {
                if (! in_array($file->getExtension(), ['php', 'css', 'js'], true)) {
                    continue;
                }

                // This page lists pictures; it must not count itself as a use.
                if (str_contains(str_replace('\\', '/', $file->getPathname()), 'MediaLibrary.php')) {
                    continue;
                }

                preg_match_all('#images/[A-Za-z0-9._/\-]+\.(?:png|jpe?g|webp|gif|svg)#i', (string) file_get_contents($file->getPathname()), $found);

                foreach (array_unique($found[0]) as $path) {
                    $uses[$path][] = str_replace('\\', '/', $file->getRelativePathname());
                }
            }
        }

        return $uses;
    }

    /**
     * Pictures named in the starting content (config files) that the admin may have replaced.
     *
     * @return array<string, array<int, string>>
     */
    private static function defaultUses(): array
    {
        $uses = [];

        foreach (glob(config_path('*.php')) ?: [] as $file) {
            $text = (string) file_get_contents($file);

            preg_match_all('#images/[A-Za-z0-9._/\-]+\.(?:png|jpe?g|webp|gif|svg)#i', $text, $found);

            foreach (array_unique($found[0]) as $path) {
                $uses[$path][] = 'config/'.basename($file);
            }

            // Icons given by name ('icon' => 'cloche') stand for one of the built-in symbol files.
            preg_match_all("#'icon'\s*=>\s*'([a-z0-9-]+)'#", $text, $names);

            foreach (array_unique($names[1]) as $name) {
                foreach (self::iconPaths($name) as $path) {
                    $uses[$path][] = 'config/'.basename($file);
                }
            }
        }

        return $uses;
    }

    /**
     * An icon field holds a picture path or a bare icon name; return every file it can mean.
     *
     * @return array<int, string>
     */
    private static function iconPaths(?string $value): array
    {
        $value = trim((string) $value);

        if ($value === '') {
            return [];
        }

        if (preg_match('#\.(png|jpe?g|webp|gif|svg)$#i', $value)) {
            return [$value];
        }

        if (! preg_match('/^[a-z0-9-]+$/', $value)) {
            return [];
        }

        return [
            "images/icons/placeholders/{$value}.svg",
            "images/icons/{$value}.png",
            "images/icons/ico-{$value}.png",
        ];
    }

    private static function clean(mixed $value): ?string
    {
        $value = trim((string) $value);
        $value = ltrim(explode('?', $value)[0], '/');

        return str_starts_with($value, 'images/') ? $value : null;
    }

    private static function typeOf(string $path, string $extension, ?array $size): string
    {
        if (str_starts_with($path, 'images/icons/placeholders/')) {
            return 'symbol';
        }

        if (str_contains(strtolower(basename($path)), 'logo')) {
            return 'logo';
        }

        if (str_starts_with($path, 'images/icons/') || str_starts_with($path, 'images/reviews/') || $extension === 'svg') {
            return 'icon';
        }

        if (in_array($extension, ['png', 'webp'], true) && $size && max($size[0], $size[1]) <= 640) {
            return 'icon';
        }

        return 'photo';
    }

    /**
     * The upload time is in the name of files uploaded through the admin (20261016_071433_...);
     * other files use the time they were last changed.
     */
    private static function uploadedAt(string $path, string $absolute): Carbon
    {
        if (preg_match('/^(\d{8}_\d{6})_/', basename($path), $m)) {
            try {
                return Carbon::createFromFormat('Ymd_His', $m[1]);
            } catch (\Throwable) {
                // fall through to the file time
            }
        }

        return Carbon::createFromTimestamp((int) @filemtime($absolute));
    }

    /**
     * The name the file had before upload, so several uploads of the same picture group together.
     */
    private static function originalName(string $path): string
    {
        $name = strtolower(basename($path));
        $name = preg_replace('/^\d{8}_\d{6}_/', '', $name);
        $name = preg_replace('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}_/', '', $name);

        return dirname($path).'/'.$name;
    }
}
