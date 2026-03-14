<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\Gate::define('viewPulse', function (\App\Models\User $user) {
            return true; // Allow all users for now in development
        });

        // Register Prometheus Collectors
        \Spatie\Prometheus\Facades\Prometheus::addGauge('http_requests_total')
            ->helpText('Total HTTP requests')
            ->value(function () {
                return (int) \Illuminate\Support\Facades\Cache::get('prometheus:http_requests_total', 0);
            });

        \Spatie\Prometheus\Facades\Prometheus::addGauge('http_errors_total')
            ->helpText('Total HTTP errors')
            ->value(function () {
                return (int) \Illuminate\Support\Facades\Cache::get('prometheus:http_errors_total', 0);
            });

        \Spatie\Prometheus\Facades\Prometheus::addGauge('db_queries_total')
            ->helpText('Total database queries')
            ->value(function () {
                return (int) \Illuminate\Support\Facades\Cache::get('prometheus:db_queries_total', 0);
            });

        \Spatie\Prometheus\Facades\Prometheus::addGauge('db_query_duration_last')
            ->helpText('Duration of the last database query in seconds')
            ->value(function () {
                return (float) \Illuminate\Support\Facades\Cache::get('prometheus:db_query_duration_last', 0);
            });

        \Spatie\Prometheus\Facades\Prometheus::addGauge('http_request_duration_last')
            ->helpText('Duration of the last HTTP request in seconds')
            ->value(function () {
                return (float) \Illuminate\Support\Facades\Cache::get('prometheus:http_request_duration_last', 0);
            });

        \Spatie\Prometheus\Facades\Prometheus::addGauge('active_users')
            ->helpText('Approximate number of active users in the last 5 minutes')
            ->value(function () {
                return (int) \Illuminate\Support\Facades\Cache::get('prometheus:active_users_count', 0);
            });

        // Test Log to Verify Loki
        \Illuminate\Support\Facades\Log::info('Observability System Booted', [
            'app_name' => config('app.name'),
            'env' => config('app.env')
        ]);

        // DB Query Listener
        \Illuminate\Support\Facades\DB::listen(function ($query) {
            static $isRecording = false;
            if ($isRecording) return;
            $isRecording = true;
            try {
                if (!\Illuminate\Support\Facades\Cache::has('prometheus:db_queries_total')) {
                    \Illuminate\Support\Facades\Cache::forever('prometheus:db_queries_total', 0);
                }
                \Illuminate\Support\Facades\Cache::increment('prometheus:db_queries_total');
                \Illuminate\Support\Facades\Cache::put('prometheus:db_query_duration_last', $query->time / 1000);
            } finally {
                $isRecording = false;
            }
        });
    }
}
