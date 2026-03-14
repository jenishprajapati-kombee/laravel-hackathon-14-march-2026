<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;

class AnomalyService
{
    protected const CACHE_KEY_DELAY = 'anomaly:delay';
    protected const CACHE_KEY_ERROR_RATE = 'anomaly:error_rate';
    protected const CACHE_KEY_INEFFICIENT_DB = 'anomaly:inefficient_db';

    public function injectPerformanceIssues()
    {
        // 1. Inject Delay
        $delay = Cache::get(self::CACHE_KEY_DELAY, 0);
        if ($delay > 0) {
            usleep($delay * 1000); // delay in milliseconds
        }

        // 2. Inject Random Errors
        $errorRate = Cache::get(self::CACHE_KEY_ERROR_RATE, 0);
        if ($errorRate > 0 && rand(1, 100) <= $errorRate) {
            throw new Exception("Simulated Anomaly Error: Something went wrong in the system logic.");
        }

        // 3. Inefficient DB logic
        $inefficient = Cache::get(self::CACHE_KEY_INEFFICIENT_DB, false);
        if ($inefficient) {
            // Simulate N+1 or heavy load by fetching unrelated data multiple times
            for ($i = 0; $i < 50; $i++) {
                \App\Models\User::count();
            }
        }
    }

    public function setDelay(int $ms)
    {
        Cache::put(self::CACHE_KEY_DELAY, $ms);
    }

    public function setErrorRate(int $percentage)
    {
        Cache::put(self::CACHE_KEY_ERROR_RATE, $percentage);
    }

    public function setInefficientDB(bool $enabled)
    {
        Cache::put(self::CACHE_KEY_INEFFICIENT_DB, $enabled);
    }

    public function reset()
    {
        Cache::forget(self::CACHE_KEY_DELAY);
        Cache::forget(self::CACHE_KEY_ERROR_RATE);
        Cache::forget(self::CACHE_KEY_INEFFICIENT_DB);
    }

    public function getStatus()
    {
        return [
            'delay_ms' => Cache::get(self::CACHE_KEY_DELAY, 0),
            'error_rate' => Cache::get(self::CACHE_KEY_ERROR_RATE, 0),
            'inefficient_db' => Cache::get(self::CACHE_KEY_INEFFICIENT_DB, false),
        ];
    }
}
