<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'web_posts';
    public $timestamps = true;
    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail_path',
        'views_count',
        'is_published',
        'published_at',
    ];

    public function PostCategory()
    {
        return $this->belongsTo(PostCategory::class);
    }
    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
