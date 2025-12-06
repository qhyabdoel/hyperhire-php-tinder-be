<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'pictures',
        'location',
        'like_count',
        'notified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'pictures' => 'array',
            'location' => 'array',
            'notified' => 'boolean',
        ];
    }

    /**
     * Get the users that this user has liked.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'user_likes', 'user_id', 'person_id')
            ->withPivot('is_liked')
            ->wherePivot('is_liked', true);
    }

    /**
     * Get the likes that this user has created.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */  
    public function userLikes()
    {
        return $this->hasMany(UserLike::class, 'user_id');
    }

    /**
     * Get the likes that this user has received.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */  
    public function receivedLikes()
    {
        return $this->hasMany(UserLike::class, 'person_id');
    }
}
