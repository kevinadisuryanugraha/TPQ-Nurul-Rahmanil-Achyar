<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LangkahPanduan extends Model
{
    protected $table = 'langkah_panduans';
    
    public $timestamps = false;

    protected $fillable = [
        'panduan_praktik_id',
        'nomor_urut',
        'judul_langkah',
        'deskripsi',
        'gambar',
    ];

    public function panduanPraktik()
    {
        return $this->belongsTo(PanduanPraktik::class, 'panduan_praktik_id');
    }
}
