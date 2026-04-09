<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ScannerService;

class ScanController extends Controller
{
    protected $scannerService;

    public function __construct(ScannerService $scannerService)
    {
        $this->scannerService = $scannerService;
    }

    public function start(Request $request)
    {
        $url = $request->input('url');

        if (!$url) {
            return response()->json([
                'error' => 'URL is required'
            ], 400);
        }

        // sementara dummy dulu
        $result = $this->scannerService->scan($url);

        return response()->json($result);
    }
}
