<?php

namespace App\Http\Controllers;

use App\Models\AppLimitRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppLimitRequestMail;

class AppLimitRequestController extends Controller
{
    public function requestLimit(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string',
            'time_limit' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        if (!$user->is_under_18) {
            return response()->json(['message' => 'Only users under 18 require approval.'], 403);
        }
        $parentEmail = $user->parent_email;
        if (!$parentEmail) {
            return response()->json(['message' => 'Parent email not found.'], 400);
        }
        $appLimitRequest = AppLimitRequest::create([
            'user_id' => $user->id,
            'app_name' => $request->app_name,
            'time_limit' => $request->time_limit,
            'parent_email' => $parentEmail,
            'is_approved' => false,
        ]);
        Mail::to($parentEmail)->send(new AppLimitRequestMail($appLimitRequest));
        return response()->json([
                                       'message' => 'Request sent to parent for approval.',
                                       'request_id' => $appLimitRequest->id
                                       ], 200);
    }
    public function approveLimit($id)
    {
        $appLimitRequest = AppLimitRequest::find($id);
        if (!$appLimitRequest) {
            return response('Request not found.', 404);
        }
        if ($appLimitRequest->is_approved) {
            return response('This request was already approved.', 200);
        }
        $appLimitRequest->update(['is_approved' => true]);
        return response('<h3 style="text-align:center;margin-top:50px;">App limit approved successfully!</h3>', 200);
    }
    public function checkStatus($id)
{
    $appLimitRequest = AppLimitRequest::find($id);

    if (!$appLimitRequest) {
        return response()->json(['status' => 'not_found'], 404);
    }

    return response()->json([
        'status' => $appLimitRequest->is_approved ? 'approved' : 'pending',
        'approved' => $appLimitRequest->is_approved
    ]);
}

    
}
