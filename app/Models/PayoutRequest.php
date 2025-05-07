<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayoutRequest extends Model
{
    use HasFactory;
    protected $fillable = ['trainer_id', 'amount', 'paypal_email'];

    public function trainer()
    {
        return $this->belongsTo(\App\Models\User::class, 'trainer_id');
    }
}
