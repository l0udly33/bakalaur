<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{

    use HasFactory;
    protected $fillable = [
        'user_id',
        'trainer_id',
        'status',
        'description',
        'price',
        'hours',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
