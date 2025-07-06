<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TimeLimitService;
use App\Models\AppLimit;
use App\Models\ScreenTime;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TimeLimitServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_limit_exceeded_returns_true_when_exceeded()
    {
        $user = User::factory()->create();
        AppLimit::create([
            'user_id' => $user->id,
            'app_name' => 'YouTube',
            'time_limit_minutes' => 60,
        ]);

        // Create screen time logs that sum to 70 minutes
        ScreenTime::create([
            'user_id' => $user->id,
            'record_date' => now()->toDateString(),
            'total_screen_time_minutes' => 70,
            'app_usage' => json_encode(['YouTube' => 70]),
        ]);

        $service = new TimeLimitService();
        $this->assertTrue($service->isLimitExceeded($user->id, 'YouTube', now()->toDateString()));
    }
}
