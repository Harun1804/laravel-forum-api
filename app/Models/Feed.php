<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feed extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'feed_like', 'feed_id', 'user_id')->withTimestamps();
    }

    public function comments()
    {
        return $this->belongsToMany(User::class, 'feed_comments', 'feed_id', 'user_id')->withPivot('body')->withTimestamps();
    }
}
