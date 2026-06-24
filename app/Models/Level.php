<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'urutan',
        'deskripsi',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'current_level_id');
    }

    public function levelHistories()
    {
        return $this->hasMany(UserLevelHistory::class, 'level_id');
    }

    public function ceritaKisahs()
    {
        return $this->hasMany(CeritaKisah::class, 'level_target_id');
    }

    public function panduanPraktiks()
    {
        return $this->hasMany(PanduanPraktik::class, 'level_target_id');
    }

    public function pengumumans()
    {
        return $this->hasMany(Pengumuman::class, 'level_target_id');
    }
}
