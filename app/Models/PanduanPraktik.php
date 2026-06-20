<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanduanPraktik extends Model
{
    protected $table = 'panduan_praktiks';

    protected $fillable = [
        'admin_id',
        'judul',
        'cover_image',
        'deskripsi',
        'jenis_praktik',
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

    public function langkahs()
    {
        return $this->hasMany(LangkahPanduan::class, 'panduan_praktik_id')->orderBy('nomor_urut', 'asc');
    }
}
