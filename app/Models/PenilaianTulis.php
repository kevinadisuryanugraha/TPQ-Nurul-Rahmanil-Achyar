<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianTulis extends Model
{
    protected $table = 'penilaian_tulises';

    protected $fillable = [
        'user_id',
        'admin_id',
        'tanggal',
        'materi',
        'nilai',
        'grade',
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
