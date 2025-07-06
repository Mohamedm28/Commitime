<?php

namespace App\Services;

use App\Models\ScreenDistanceAlert;
use Illuminate\Support\Facades\Log;

class ScreenDistanceAlertService
{
    /**
     * Analyze the screen distance and log an alert if needed.
     *
     * @param int $userId
     * @param int $distanceCm The measured distance in centimeters.
     * @return array|null Returns the alert data if an alert is logged, or null otherwise.
     */
    public function analyzeAndLogAlert(int $userId, int $distanceCm): ?array
    {
        $alertThreshold = 30; 
        
        // Simulated AI function: returns true if distance is below threshold.
        if ($this->isDistanceTooShort($distanceCm, $alertThreshold)) {
            // Log the alert in the database
            $alert = ScreenDistanceAlert::create([
                'user_id' => $userId,
                'alert_time' => now(),
                'distance_cm' => $distanceCm,
            ]);

            Log::info("Screen distance alert logged for user {$userId} at distance {$distanceCm} cm.");

            return $alert->toArray();
        }

        // If the AI analysis determines that the distance is acceptable, return null.
        return null;
    }

    /**
     * Simulate AI analysis to determine if the distance is too short.
     *
     * @param int $distanceCm
     * @param int $threshold
     * @return bool
     */
    protected function isDistanceTooShort(int $distanceCm, int $threshold): bool
    {
        // In a real implementation, you might call an external AI service.
        // Here, we simply return true if the measured distance is less than the threshold.
        return $distanceCm < $threshold;
    }
}
