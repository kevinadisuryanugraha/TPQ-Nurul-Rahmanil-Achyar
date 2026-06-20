<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianHafalan extends Model
{
    protected $table = 'penilaian_hafalans';

    protected $fillable = [
        'user_id',
        'admin_id',
        'tanggal',
        'jenis_hafalan',
        'nama_item',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
