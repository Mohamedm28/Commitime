<?php

namespace App\Services;

use App\Models\AppLimitRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppLimitRequestMail;

class AppLimitRequestService
{
    public function createLimitRequest($user, $appName, $timeLimit)
    {
        $parentEmail = $user->parent_email;
        if (!$parentEmail) {
            return response()->json(['message' => 'Parent email not found.'], 400);
        }

        $appLimitRequest = AppLimitRequest::create([
            'user_id' => $user->id,
            'app_name' => $appName,
            'time_limit' => $timeLimit,
            'parent_email' => $parentEmail,
            'is_approved' => false,
        ]);

        Mail::to($parentEmail)->send(new AppLimitRequestMail($appLimitRequest));

        return $appLimitRequest;
    }

    public function approveLimit($id)
    {
        $appLimitRequest = AppLimitRequest::find($id);
        if (!$appLimitRequest) {
            return null;
        }

        $appLimitRequest->update(['is_approved' => true]);
        return $appLimitRequest;
    }
}
