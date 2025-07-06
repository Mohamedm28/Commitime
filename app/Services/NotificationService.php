<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function createNotification(int $userId, string $title, string $message, string $type = null)
    {
        try {
            $notification = Notification::create([
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'is_read' => false,
            ]);

            return $notification;
        } catch (\Exception $e) {
            Log::error("Failed to create notification: " . $e->getMessage());
            return null;
        }
    }
}
