<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainerApplication extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'full_name', 'rank', 'age', 'experience', 'motivation'];
}
