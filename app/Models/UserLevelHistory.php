<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLevelHistory extends Model
{
    public $timestamps = false; // We use created_at raw column from migration

    protected $fillable = [
        'user_id',
        'level_id',
        'level_sebelumnya_id',
        'admin_id',
        'tipe',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function levelSebelumnya()
    {
        return $this->belongsTo(Level::class, 'level_sebelumnya_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
