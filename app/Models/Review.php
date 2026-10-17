<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * A review written by a guest on the website (1 to 5 stars and a comment).
 */
class Review extends Model
{
    protected $fillable = [
        'name',
        'place',
        'rating',
        'comment',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Reviews that are shown on the public Reviews page.
     *
     * @param  Builder<Review>  $query
     * @return Builder<Review>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * How many reviews wait for approval (always 0 while approval is switched off).
     * Used for the badge in the admin menu; never breaks a page if the table is missing.
     */
    public static function waitingForApproval(): int
    {
        if (! config('reviews.require_approval')) {
            return 0;
        }

        try {
            return static::where('is_published', false)->count();
        } catch (\Throwable) {
            return 0;
        }
    }
}
