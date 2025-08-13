<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Invitation extends Model
{
    protected $fillable = [
        'inviter_id',
        'token',
        'email',
        'max_uses',
        'uses',
        'expires_at',
        'role',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at instanceof Carbon ? $this->expires_at->isPast() : false;
    }

    public function canUse(): bool
    {
        return ! $this->isExpired() && $this->uses < $this->max_uses && $this->used_at === null;
    }
}
