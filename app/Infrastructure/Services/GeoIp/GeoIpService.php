<?php

namespace App\Infrastructure\Services\GeoIp;

use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Log;

class GeoIpService
{
    /**
     * Detect country code from request IP.
     * 
     * @param Request $request
     * @return string|null ISO country code (e.g., 'EG', 'US')
     */
    public function detect(Request $request): ?string
    {
        try {
            // For local development, we might want to mock the IP if it's 127.0.0.1
            $ip = $request->ip();
            
            if ($ip === '127.0.0.1' || $ip === '::1') {
                return config('app.env') === 'local' ? 'EG' : null;
            }

            $position = Location::get($ip);
            
            return $position ? $position->countryCode : null;
        } catch (\Exception $e) {
            Log::error("GeoIp Detection Error: " . $e->getMessage());
            return null;
        }
    }
}
