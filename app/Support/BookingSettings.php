<?php

namespace App\Support;

use App\Models\HomeContent;
use Illuminate\Support\Collection;

/**
 * Everything about online booking that the resort edits in the admin
 * (Site content > Booking): the on/off switch, the inbox that receives requests,
 * the limits, the wording of the page and the "request received" message.
 * config/pagecontent.php and config/booking.php only supply the starting values.
 */
class BookingSettings
{
    /**
     * @return array{open:bool,notify:string,max_guests:int,max_nights:int,closed_message:string}
     */
    public static function all(): array
    {
        static $cache = null;

        if ($cache !== null && ! app()->runningUnitTests()) {
            return $cache;
        }

        $row = null;

        try {
            // Looked up without the "active" filter: switching the entry OFF is how booking is paused.
            $row = HomeContent::where('section', 'booking_settings')->orderBy('sort_order')->first();
        } catch (\Throwable) {
            // table not ready yet: use the starting values
        }

        $default = config('pagecontent.booking_settings.0', []);

        $whole = fn ($value, int $fallback, int $min, int $max): int => ($n = (int) round((float) preg_replace('/[^\d.]/', '', (string) $value))) >= $min
            ? min($n, $max)
            : $fallback;

        $email = trim((string) ($row?->title ?? ''));

        return $cache = [
            'open' => $row ? (bool) $row->active : true,
            'notify' => filter_var($email, FILTER_VALIDATE_EMAIL)
                ? $email
                : (string) (config('booking.notify') ?: config('mail.from.address')),
            'max_guests' => $whole($row?->price, (int) config('booking.max_guests', 10), 1, 50),
            'max_nights' => $whole($row?->subtitle, (int) config('booking.max_nights', 30), 1, 365),
            'closed_message' => trim((string) ($row?->description ?? '')) ?: (string) ($default['description'] ?? ''),
        ];
    }

    /**
     * The page wording by position (1, 2, 3...). A label that is empty or missing in the
     * admin falls back to its starting text, so the page never shows a blank.
     *
     * @param  Collection<string, Collection<int, HomeContent>>  $contents
     * @return array<int, string>
     */
    public static function labels(Collection $contents): array
    {
        $defaults = collect(config('pagecontent.booking_labels', []))
            ->values()
            ->mapWithKeys(fn (array $row, int $i) => [$i + 1 => (string) $row['title']])
            ->all();

        $saved = array_filter(
            HomeContent::pageLabels($contents, 'booking_labels'),
            fn (string $text) => trim($text) !== ''
        );

        return $saved + $defaults;
    }

    /**
     * The "request received" page text, with :name and :email filled in.
     *
     * @param  Collection<string, Collection<int, HomeContent>>  $contents
     * @return array{eyebrow:string,title:string,lead:string}
     */
    public static function confirmation(Collection $contents, string $name, string $email): array
    {
        $default = config('pagecontent.booking_confirmation.0', []);
        $row = HomeContent::pageRow($contents, 'booking_confirmation');
        $pick = fn (string $field) => trim((string) ($row?->{$field} ?? '')) ?: (string) ($default[$field] ?? '');
        $fill = fn (string $text) => strtr($text, [':name' => $name, ':email' => $email]);

        return [
            'eyebrow' => $pick('subtitle'),
            'title' => $fill($pick('title')),
            'lead' => $fill($pick('description')),
        ];
    }
}
