<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Streak;
use App\Models\User;
use Carbon\Carbon;

class StreakController extends Controller
{
    public function show($userId)
    {
        // Ensure the user exists
        $user = User::findOrFail($userId);

        // Retrieve or create streak entry
        $streak = Streak::firstOrCreate(
            ['user_id' => $userId],
            ['days_count' => 0, 'last_updated' => Carbon::now()->format('Y-m-d')]
        );

        // Get dates for comparison
        $lastUpdated = Carbon::parse($streak->last_updated);
        $today = Carbon::now();

        // Check if the streak should be updated
        if ($lastUpdated->isToday()) {
            // Already updated today, do nothing
        } elseif ($lastUpdated->diffInDays($today) === 1) {
            // Consecutive day, increment streak
            $streak->days_count += 1;
            $streak->last_updated = $today->format('Y-m-d');
            $streak->save();
        } else {
            // Non-consecutive day, reset streak
            $streak->days_count = 1;
            $streak->last_updated = $today->format('Y-m-d');
            $streak->save();
        }

        return response()->json([
            'user_id' => $streak->user_id,
            'days_count' => $streak->days_count,
            'last_updated' => $streak->last_updated
        ], 200);
    }
}
