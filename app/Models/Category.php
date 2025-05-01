<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['nep_title', 'eng_title', 'slug', 'meta_keywords', 'meta_description'];

    public function articles()
{
    return $this->belongsToMany(Article::class, 'article_category');
}
}
