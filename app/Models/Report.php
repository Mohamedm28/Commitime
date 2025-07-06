<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['child_id', 'title', 'content', 'sent_at'];

    public function child()
    {
        return $this->belongsTo(User::class, 'child_id');
    }
}

