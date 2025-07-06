<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppLimitRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'app_name', 'time_limit', 'parent_email', 'is_approved'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

