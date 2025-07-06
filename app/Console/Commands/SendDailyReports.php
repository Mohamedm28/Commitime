<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\ParentalReportService;

class SendDailyReports extends Command
{
    protected $signature = 'reports:daily';
    protected $description = 'Send daily screen time reports to parents for users under 18';
    protected $reportService;

    public function __construct(ParentalReportService $reportService)
    {
        parent::__construct();
        $this->reportService = $reportService;
    }

    public function handle()
    {
        // Get all children (users under 18) with a parent email
        $children = User::where('is_under_18', true)->whereNotNull('parent_email')->get();

        foreach ($children as $child) {
            $reportData = $this->reportService->generateReport($child);
            $this->reportService->sendReportToParent($child, $reportData);
        }

        $this->info('Daily reports sent successfully!');
    }
}
