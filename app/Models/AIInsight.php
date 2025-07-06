<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIInsight extends Model {
    use HasFactory;
    protected $table = 'ai_insights';
    protected $fillable = [
        'user_id', 
        'insight_date',
        'reflection_question',
        'user_response',
        'analysis', 
        'recommendations'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function getRecommendationsAttribute($value) {
        return json_decode($value, true);
    }

    public function setRecommendationsAttribute($value) {
        $this->attributes['recommendations'] = json_encode($value);
    }
}
