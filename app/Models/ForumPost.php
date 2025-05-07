<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'user_id', 'pinned', 'upvotes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(ForumComment::class);
    }

    public function votes()
    {
        return $this->hasMany(ForumPostVote::class);
    }

    public function hasVotedBy($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }
}
