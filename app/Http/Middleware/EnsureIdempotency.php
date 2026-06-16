<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class EnsureIdempotency
{
    private const TTL = 60 * 60 * 24;

    /**
     * @param  \Closure(Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('Idempotency-Key');

        if (!$key) {
            return response()->json(['message' => 'Idempotency-Key header is required.'], 422);
        }

        $cacheKey = 'idempotency:' . $request->route()->getName() . ':' . $key;

        if (Cache::has($cacheKey . ':lock')) {
            return response()->json(['message' => 'A request with this Idempotency-Key is already in progress.'], 409);
        }

        if ($cached = Cache::get($cacheKey)) {
            return response()->json($cached['body'], $cached['status']);
        }

        Cache::put($cacheKey . ':lock', true, self::TTL);

        try {
            $response = $next($request);

            Cache::put($cacheKey, [
                'status' => $response->getStatusCode(),
                'body' => json_decode($response->getContent(), true),
            ], self::TTL);
        } finally {
            Cache::forget($cacheKey . ':lock');
        }

        return $response;
    }
}
