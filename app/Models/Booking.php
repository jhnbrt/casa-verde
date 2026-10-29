<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public const STATUSES = ['pending', 'confirmed', 'cancelled'];

    protected $fillable = [
        'reference', 'villa_slug', 'villa_title', 'check_in', 'check_out', 'nights',
        'adults', 'children', 'name', 'email', 'phone', 'country', 'special_requests',
        'total', 'status', 'terms_accepted_at', 'cloudbeds_reservation_id',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total' => 'decimal:2',
        'terms_accepted_at' => 'datetime',
    ];

    public static function pendingCount(): int
    {
        try {
            return static::where('status', 'pending')->count();
        } catch (\Throwable) {
            return 0; // table not migrated yet
        }
    }
}
