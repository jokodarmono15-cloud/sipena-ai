<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGeofence
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('latitude') && $request->has('longitude')) {
            $schoolLat = env('SCHOOL_LAT', -0.9120);
            $schoolLng = env('SCHOOL_LNG', 104.7340);
            $radius = env('GEOFENCE_RADIUS', 500);

            $distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $schoolLat,
                $schoolLng
            );

            if ($distance > $radius) {
                return response()->json([
                    'error' => 'Anda berada di luar area sekolah. Jarak: ' . round($distance) . 'm'
                ], 403);
            }
        }

        return $next($request);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
              cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
              sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
