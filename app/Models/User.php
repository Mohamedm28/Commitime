<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable {
    use HasApiTokens, HasFactory,Notifiable; 
    protected $fillable = [
        'first_name', 
        'last_name',
        'email',
        'password',
        'is_under_18',
        'parent_email'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'is_under_18' => 'boolean',
    ];
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_email', 'email');
    }


    public function dailyReports() {
        return $this->hasMany(DailyReport::class);
    }

    public function streaks() {
        return $this->hasMany(Streak::class);
    }

    public function app_limit_requests() {
        return $this->hasMany(AppLimitRequest::class);
    }

    public function screenDistanceAlerts() {
        return $this->hasMany(ScreenDistanceAlert::class);
    }
    public function screen_times() {
        return $this->hasMany(ScreenTime::class);
    }
    public function ai_insights() {
        return $this->hasMany(AIInsight::class);
    }
  

}

