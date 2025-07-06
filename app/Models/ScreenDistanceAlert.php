<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreenDistanceAlert extends Model {
    use HasFactory;
    protected $fillable = [
        'user_id',
        'alert_time', 
        'distance_cm'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

