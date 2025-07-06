<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model {
    use HasFactory;
    protected $fillable = [
        'user_id', 
        'report_date', 
        'screen_time_minutes', 
        'app_usage_details'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function getAppUsageDetailsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setAppUsageDetailsAttribute($value)
    {
        $this->attributes['app_usage_details'] = json_encode($value);
    }
}
