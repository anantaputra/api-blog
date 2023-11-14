<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'deleted_at',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }
    
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
