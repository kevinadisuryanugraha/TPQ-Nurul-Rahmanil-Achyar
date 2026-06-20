<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CeritaKisah extends Model
{
    protected $table = 'cerita_kisahs';

    protected $fillable = [
        'admin_id',
        'judul',
        'thumbnail',
        'konten',
        'kategori',
        'level_target_id',
        'status',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function levelTarget()
    {
        return $this->belongsTo(Level::class, 'level_target_id');
    }
}
