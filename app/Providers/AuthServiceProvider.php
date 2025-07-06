<?php
namespace App\Providers;

use App\Models\ScreenTime;
use App\Policies\ScreenTimePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        ScreenTime::class => ScreenTimePolicy::class,
        // Add other policies here
    ];

    public function boot() {
        $this->registerPolicies();
    }
}
