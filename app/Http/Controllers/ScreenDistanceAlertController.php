<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ScreenDistanceAlertService;

class ScreenDistanceAlertController extends Controller
{
    protected $alertService;

    public function __construct(ScreenDistanceAlertService $alertService)
    {
        $this->alertService = $alertService;
    }

    /**
     * API endpoint to log a screen distance alert based on AI analysis.
     */
    public function logAlert(Request $request)
    {
        // Ensure user is authenticated
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validate request
        $validated = $request->validate([
            'distance_cm' => 'required|numeric|min:1',
        ]);

        // Process alert
        $alert = $this->alertService->analyzeAndLogAlert($user->id, $validated['distance_cm']);

        return response()->json([
            'message' => $alert ? 'Screen distance alert logged.' : 'No alert triggered. Distance is acceptable.',
            'alert' => $alert,
        ]);
    }
}
