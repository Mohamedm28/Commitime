<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ParentalReportService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Report;
use App\Models\DailyReport;


class DailyReportController extends Controller
{
    protected $reportService;

    public function __construct(ParentalReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function sendDailyReport(Request $request)
    {
        try {
            $child = Auth::user();
    
            if (!$child) {
                return response()->json(['message' => 'User not authenticated.'], 401);
            }
    
            if (!$child->is_under_18) {
                return response()->json(['message' => 'Daily reports are only for users under 18.'], 403);
            }
    
            if (empty($child->parent_email)) {
                return response()->json(['message' => 'No parent email registered.'], 403);
            }
    
            $appUsageDetails = json_decode($request->input('app_usage_details'), true);
    
            if (!is_array($appUsageDetails)) {
                return response()->json(['message' => 'Invalid app usage details format.'], 400);
            }
            $report = DailyReport::create([
                'user_id' => $child->id, 
                'report_date' => $request->input('report_date'),
                'screen_time_minutes' => $request->input('screen_time_minutes'),
                'app_usage_details' => json_encode($appUsageDetails),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->reportService->sendReportToParent($child, $report->toArray());
            return response()->json(['message' => 'Daily report sent and stored successfully.', 'data' => $report], 200);
        } catch (\Exception $e) {
            \Log::error('Error sending daily report: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send daily report. Please try again later.'], 500);
        }
    }
    

    public function getReports()
{
    try {
        $child = Auth::user();

        if (!$child) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        $reports = DailyReport::where('user_id', $child->id)
                              ->orderBy('created_at', 'desc')
                              ->get();

        if ($reports->isEmpty()) {
            return response()->json([
                'message' => 'No reports found for this child.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Reports retrieved successfully.',
            'data' => $reports
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Error fetching reports: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to retrieve reports.'], 500);
    }
}

    
}
