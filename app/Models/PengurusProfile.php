<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengurusProfile extends Model
{
    use HasFactory;

    protected $table = 'pengurus_profiles';

    protected $fillable = [
        'nama',
        'jabatan',
        'foto',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Scope active profiles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
