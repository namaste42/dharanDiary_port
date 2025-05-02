<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Testing\Fluent\Concerns\Has;

class Advertise extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name',
        'slug',
        'contact',
        'image',
        'expire_date',
        'redirect_url',
        'location',
    ];

    public function scopeActive(Builder $query): void
    {
        $query->where(function ($q) {
            $q->whereNull('expire_date')->orWhere('expire_date', '>', now());
        });
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expire_date !== null && $this->expire_date->isPast();
    }
}
