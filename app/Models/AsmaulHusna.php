<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsmaulHusna extends Model
{
    protected $table = 'asmaul_husnas';

    protected $fillable = [
        'urutan',
        'arab',
        'latin',
        'arti',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];
}
