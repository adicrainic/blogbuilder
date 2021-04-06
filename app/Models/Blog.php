<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'post', 'post_excerpt', 'slug', 'user_id', 'featuredImage', 'metaDescription', 'views', 'jsonData'
    ];

    public function setTitleAttribute($title){
        $this->attributes['slug'] = $this->uniqueSlug($title);
        $this->attributes['title'] = $title;
    }

    private function uniqueSlug($title){
        $slug = Str::slug($title,'-');
        $count = Blog::where('slug','like',"{$slug}%")->count();
        $newCount = $count > 0 ? ++$count : '';
        return $newCount > 0 ? "$slug-$newCount" : $slug;
    }
}
