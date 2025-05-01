<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
