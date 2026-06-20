<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hadist extends Model
{
    protected $table = 'hadiths';

    protected $fillable = [
        'teks_arab',
        'terjemahan',
        'sumber_kitab',
        'perawi',
        'kategori',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
