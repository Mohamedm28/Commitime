<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreenTime extends Model {
    use HasFactory;
    protected $fillable = [
        'user_id',
        'record_date', 
        'total_screen_time_minutes', 
        'app_usage'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function getAppUsageAttribute($value) {
        return json_decode($value, true);
    }

    public function setAppUsageAttribute($value) {
        $this->attributes['app_usage'] = json_encode($value);
    }
}
