<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlashcardDeck extends Model
{
    protected $fillable = ['nama', 'deskripsi', 'source_type', 'level_target_id', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(FlashcardItem::class, 'deck_id')->orderBy('urutan');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_target_id');
    }
}
