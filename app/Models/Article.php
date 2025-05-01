<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'eng_title',
        'slug',
        'image',
        'content',
        'author',
        'views',
        'status',
        'meta_keywords',
        'meta_description',
    ];

    public function categories()
{
    return $this->belongsToMany(Category::class, 'article_category');
}

public function author()
{
    return $this->belongsTo(User::class, 'author_id'); // Or Author::class if using custom author model
}

//
}
