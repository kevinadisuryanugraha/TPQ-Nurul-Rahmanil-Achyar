<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surah extends Model
{
    protected $table = 'surahs';
    
    public $incrementing = false; // Key is Surah number (1-114)
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'nama_arab',
        'nama_latin',
        'nama_indonesia',
        'arti',
        'tempat_turun',
        'jumlah_ayat',
    ];

    public function ayats()
    {
        return $this->hasMany(Ayat::class, 'surah_id');
    }
}
