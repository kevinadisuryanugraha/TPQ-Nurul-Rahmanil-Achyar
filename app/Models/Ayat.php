<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ayat extends Model
{
    protected $table = 'ayats';
    
    public $timestamps = false;

    protected $fillable = [
        'surah_id',
        'nomor_ayat',
        'teks_arab',
        'teks_latin',
        'terjemahan',
    ];

    public function surah()
    {
        return $this->belongsTo(Surah::class, 'surah_id');
    }
}
