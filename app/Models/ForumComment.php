<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    use HasFactory;

    protected $fillable = ['forum_post_id', 'user_id', 'comment', 'pinned'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(ForumPost::class, 'forum_post_id');
    }

    public function votes()
    {
        return $this->hasMany(ForumCommentVote::class);
    }

    public function hasVotedBy($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }
}

