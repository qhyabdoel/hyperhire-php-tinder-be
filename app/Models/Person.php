<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'age', 'pictures', 'location', 'like_count', 'notified'];

    protected $casts = [
        'pictures' => 'array',
        'location' => 'array',
        'notified' => 'boolean',
    ];

    public function likers()
    {
        return $this->belongsToMany(User::class, 'user_likes')
            ->withPivot('is_liked')
            ->wherePivot('is_liked', true);
    }

    public function userLikes()
    {
        return $this->hasMany(UserLike::class);
    }
}