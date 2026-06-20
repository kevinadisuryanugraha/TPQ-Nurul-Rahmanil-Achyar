<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doa extends Model
{
    protected $table = 'duas';

    protected $fillable = [
        'judul',
        'teks_arab',
        'transliterasi',
        'terjemahan',
        'kategori',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
