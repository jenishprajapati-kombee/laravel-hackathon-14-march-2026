<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Keepsuit\LaravelOpenTelemetry\Facades\Logger;
use Symfony\Component\HttpFoundation\Response;

class PrometheusMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        $duration = microtime(true) - $start;
        $status = $response->getStatusCode();

        // Increment HTTP requests total
        if (!Cache::has('prometheus:http_requests_total')) {
            Cache::forever('prometheus:http_requests_total', 0);
        }
        Cache::increment('prometheus:http_requests_total');
        
        // Track Active Users
        $sessionId = session()->getId();
        $activeUsers = Cache::get('prometheus:active_users_list', []);
        $activeUsers[$sessionId] = time();
        $activeUsers = array_filter($activeUsers, fn($t) => $t > (time() - 300));
        Cache::put('prometheus:active_users_list', $activeUsers, 600);
        Cache::put('prometheus:active_users_count', count($activeUsers), 600);

        // Log request info for Loki
        Logger::info("HTTP Request: {$request->method()} {$request->path()}", [
            'status' => $status,
            'duration' => $duration,
            'ip' => $request->ip()
        ]);

        if ($status >= 400) {
            if (!Cache::has('prometheus:http_errors_total')) {
                Cache::forever('prometheus:http_errors_total', 0);
            }
            Cache::increment('prometheus:http_errors_total');
            Logger::error("HTTP Error $status on {$request->path()}");
        }
        
        Cache::put('prometheus:http_request_duration_last', $duration);

        return $response;
    }
}
