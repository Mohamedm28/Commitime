<?php

namespace App\Services;

use App\Models\ScreenTime;
use App\Models\DailyReport;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyReportMail;

class ParentalReportService
{
    /**
     * Generate a daily report for a child.
     *
     * @param \App\Models\User $child
     * @return array
     */
    public function generateReport($child): array
    {
        $today = now()->toDateString();
        $totalScreenTime = ScreenTime::where('user_id', $child->id)
            ->whereDate('record_date', $today)
            ->sum('total_screen_time_minutes');
        $appUsage = ScreenTime::where('user_id', $child->id)
           ->whereDate('record_date', $today)
           ->selectRaw('app_usage, SUM(total_screen_time_minutes) as total_time')
           ->groupBy('app_usage')
           ->get()
           ->mapWithKeys(function ($item) {
               return [$item->app_usage => $item->total_time];
           })
           ->toArray();
        $reportData = [
            'report_date' => $today,
            'screen_time_minutes' => $totalScreenTime,
            'app_usage_details' => json_encode(array_values($appUsage)), 
        ];
        DailyReport::create([
            'user_id' => $child->id,
            'report_date' => $today,
            'screen_time_minutes' => $totalScreenTime,
            'app_usage_details' => json_encode($appUsage),  
        ]);
        return $reportData;
    }

    /**
     * Send a daily report email to the parent.
     *
     * @param \App\Models\User $child
     * @param array $reportData
     */
    public function sendReportToParent($child, array $reportData)
    {
        if (is_string($reportData['app_usage_details'])) {
            $reportData['app_usage_details'] = json_decode($reportData['app_usage_details'], true);
        }
    
        if ($child->parent_email) {
            Mail::to($child->parent_email)
                ->send(new DailyReportMail($child, $reportData));
        }
    }
    

    //weekly overview
    public function generateWeeklyOverview($child)
    {
    $startOfWeek = now()->startOfWeek()->toDateString();
    $endOfWeek = now()->endOfWeek()->toDateString();

    // Get daily total screen time for each day of the week
    $weeklyData = ScreenTime::where('user_id', $child->id)
        ->whereBetween('record_date', [$startOfWeek, $endOfWeek])
        ->selectRaw('record_date, SUM(total_screen_time_minutes) as total')
        ->groupBy('record_date')
        ->orderBy('record_date', 'asc')
        ->pluck('total', 'record_date')
        ->toArray();

    // Structure the weekly overview
    $weeklyOverview = [
        'start_date' => $startOfWeek,
        'end_date' => $endOfWeek,
        'daily_usage' => $weeklyData,
        'total_weekly_usage' => array_sum($weeklyData)
    ];

    return $weeklyOverview;
   }

}
