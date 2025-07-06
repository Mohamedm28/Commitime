<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Streak;
use Carbon\Carbon;
use App\Models\Notification;


class UpdateStreaks extends Command
{
    protected $signature = 'streaks:update';
    protected $description = 'Update user streaks daily based on screen time compliance';

    public function handle()
    {
        $today = Carbon::today();
        
        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            $streak = Streak::firstOrCreate(['user_id' => $user->id]);

            // Get today's screen time record
            $screenTime = $user->screenTimes()->whereDate('record_date', $today)->first();

            if ($screenTime && $screenTime->total_screen_time_minutes <= $user->daily_limit_minutes) {
                // If streak was last updated yesterday, continue the streak
                if ($streak->last_updated_date == $today->copy()->subDay()) {
                    $streak->streak_days += 1;
                } else {
                    // If streak is older, reset to 1
                    $streak->streak_days = 1;
                }
                $streak->last_updated_date = $today;
            } else {
                // Streak resets if screen time exceeded or no record exists
                $streak->streak_days = 0;
            }

            $streak->save();

            if ($streak->streak_days == 7 || $streak->streak_days == 30) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Congratulations!',
                    'message' => "You've reached a {$streak->streak_days}-day streak! Keep going!",
                ]);
            }
            
        }

        $this->info('Streaks updated successfully.');
    }
}
