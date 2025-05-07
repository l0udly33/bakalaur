<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_picture',
        'description',
        'languages',
        'rank',
        'pricing',
        'availability',
    ];

    protected $casts = [
        'pricing' => 'array',
        'availability' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    //public function profile()
    //{
    //    return $this->hasOne(TrainerProfile::class);
    //}
}
