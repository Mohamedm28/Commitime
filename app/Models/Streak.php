<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Streak extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'days_count',
        'last_updated'
    ];

    protected $dates = [
        'last_updated'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
