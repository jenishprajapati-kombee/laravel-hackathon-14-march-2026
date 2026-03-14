<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AnomalyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip anomalies for pulse and assets
        if ($request->is('pulse*') || $request->is('livewire/livewire.js') || $request->is('_debugbar*')) {
            return $next($request);
        }

        // 10% chance of a slow request
        if (rand(1, 100) <= 10) {
            usleep(rand(1000, 3000) * 1000); // 1-3 seconds
        }

        // 2% chance of a random explosion
        if (rand(1, 100) <= 2) {
            abort(500, 'Simulated Chaos: This is an intentional anomaly for observability testing.');
        }

        return $next($request);
    }
}
