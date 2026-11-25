<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'web_post_categories';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'slug',
    ];

    public function Post()
    {
        return $this->hasMany(Post::class);
    }
}
