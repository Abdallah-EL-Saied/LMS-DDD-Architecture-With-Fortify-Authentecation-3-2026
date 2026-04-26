<?php

namespace App\Http\Middleware;

use App\Infrastructure\Services\GeoIp\GeoIpService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectUserCountry
{
    public function __construct(protected GeoIpService $geoIpService) {}

    /**
     * Detect user country and store in session + cookie.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $country = $this->geoIpService->detect($request);

        if ($country) {
            session(['user_country' => $country]);
        }

        $response = $next($request);

        // Set cookie for 1 year if not already set
        if ($country && !$request->cookie('user_country')) {
            $response->cookie('user_country', $country, 60 * 24 * 365); // 1 year
        }

        return $response;
    }
}
