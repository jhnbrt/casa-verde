<?php

namespace App\Models;

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
        'features' => 'array',
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];
}