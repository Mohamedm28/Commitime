<?php

namespace App\Services;

use App\Models\ScreenTime;

class ScreenTimeService
{
    /**
     * Calculate the total screen time for a user on a given date.
     *
     * @param int $userId
     * @param string $date (YYYY-MM-DD)
     * @return int
     */
    public function calculateTotalScreenTime(int $userId, string $date): int
    {
        return ScreenTime::where('user_id', $userId)
            ->whereDate('record_date', $date)
            ->sum('total_screen_time_minutes');
    }

    /**
     * Save screen time data.
     *
     * @param int $userId
     * @param array $data
     * @return ScreenTime
     */
    public function recordScreenTime(int $userId, array $data)
    {
        $data['user_id'] = $userId; 

        if (isset($data['app_usage']) && is_array($data['app_usage'])) {
            $data['app_usage'] = json_encode($data['app_usage']);
        }
        
        return ScreenTime::create($data);
    }
}
