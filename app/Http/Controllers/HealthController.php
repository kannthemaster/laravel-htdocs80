<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HealthController extends Controller
{
    public function index(): JsonResponse
    {
        // Database check
        $dbStatus = 'ok';
        try {
            DB::connection()->getPdo();
        } catch (\Throwable $e) {
            $dbStatus = 'error: ' . $e->getMessage();
        }

        // Storage check
        $storageStatus = 'ok';
        try {
            $tmp = 'healthz_' . uniqid() . '.txt';
            $disk = Storage::disk('local');
            $disk->put($tmp, 'ok');
            if (! $disk->exists($tmp)) {
                $storageStatus = 'error: not_writable';
            }
            $disk->delete($tmp);
        } catch (\Throwable $e) {
            $storageStatus = 'error: ' . $e->getMessage();
        }

        // Version check
        $version = 'unknown';
        try {
            $composer = @json_decode(@file_get_contents(base_path('composer.json')), true, 512, JSON_THROW_ON_ERROR);
            if (is_array($composer) && isset($composer['version']) && is_string($composer['version'])) {
                $version = $composer['version'];
            }
        } catch (\Throwable $e) {
            // keep unknown
        }

        $serverTime = now()->toIso8601String();

        return response()->json([
            'database' => $dbStatus,
            'storage' => $storageStatus,
            'version' => $version,
            'server_time' => $serverTime,
        ]);
    }
}
