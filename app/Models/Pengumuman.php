<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumumans';

    protected $fillable = [
        'admin_id',
        'judul',
        'isi',
        'target_semua',
        'level_target_id',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status',
    ];

    protected $casts = [
        'target_semua' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
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
