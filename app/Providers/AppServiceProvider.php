<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            // Using a try-catch block to prevent issues during migration/seeding if table doesn't exist yet
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settings = Setting::first();
                if ($settings) {
                    View::share('settings', $settings);
                }
            }
        } catch (\Exception $e) {
            // Log or ignore
        }
    }
}
