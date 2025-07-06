<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\ScreenTimeService::class, function ($app) {
            return new \App\Services\ScreenTimeService();
        });
    
        $this->app->singleton(\App\Services\AIInsightsService::class, function ($app) {
            return new \App\Services\AIInsightsService();
        });
    
        $this->app->singleton(\App\Services\NotificationService::class, function ($app) {
            return new \App\Services\NotificationService();
        });
    
        $this->app->singleton(\App\Services\ParentalReportService::class, function ($app) {
            return new \App\Services\ParentalReportService();
        });

        $this->app->singleton(\App\Services\AppLimitRequestService::class, function ($app) {
            return new \App\Services\AppLimitRequestService();
        });

        $this->app->singleton(\App\Services\ScreenDistanceAlertService::class, function ($app) {
            return new \App\Services\ScreenDistanceAlertService();
        });

        
    
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
