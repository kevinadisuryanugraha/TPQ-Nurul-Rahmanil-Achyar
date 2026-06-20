<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    use HasFactory;

    protected $table = 'testimonis';

    protected $fillable = [
        'nama',
        'role',
        'foto',
        'isi',
        'rating',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
        'rating' => 'integer',
    ];

    /**
     * Scope active testimonials.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
