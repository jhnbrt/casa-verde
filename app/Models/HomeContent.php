<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

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
