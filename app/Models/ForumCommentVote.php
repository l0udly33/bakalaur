<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ForumCommentVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'forum_comment_id',
        'user_id',
    ];

    public function comment()
    {
        return $this->belongsTo(ForumComment::class, 'forum_comment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
