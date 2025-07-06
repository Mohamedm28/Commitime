<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ScreenTimeService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class ScreenTimeController extends Controller
{
    protected $screenTimeService;

    public function __construct(ScreenTimeService $screenTimeService)
    {
        $this->screenTimeService = $screenTimeService;
    }

    /**
     * Record screen time data.
     */
    public function recordScreenTime(Request $request)
    {
        $userId = Auth::id(); 
        if (!$userId) {
        return response()->json(['error' => 'Unauthorized'], 401);
        }
        $validator = Validator::make($request->all(), [
            'record_date' => 'nullable|date',
            'total_screen_time_minutes' => 'required|integer|min:1',
            'app_usage' => json_decode($request->input('app_usage'), true),
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $data = $validator->validated();
        $data['record_date'] = $data['record_date'] ?? now()->toDateString();
        $screenTime = $this->screenTimeService->recordScreenTime($request->user()->id, $data);
        return response()->json([
            'message' => 'Screen time recorded successfully.',
            'data' => $screenTime
        ], 201);
    }
    public function getTodayScreenTime(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
        return response()->json(['error' => 'Unauthorized'], 401);
        }
        $userId = $request->user()->id;
        $today = now()->toDateString();
        $total = $this->screenTimeService->calculateTotalScreenTime($userId, $today);
        return response()->json([
            'date' => $today,
            'total_screen_time_minutes' => $total,
        ]);
    }
}
