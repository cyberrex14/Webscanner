<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ScannerService;
use App\Models\Scan;

class ScanController extends Controller
{
    protected $scannerService;

    public function __construct(ScannerService $scannerService)
    {
        $this->scannerService = $scannerService;
    }

    // 🔥 POST: start scan
    public function start(Request $request)
    {
        $url = $request->input('url');

        if (!$url) {
            return response()->json([
                'error' => 'URL is required'
            ], 400);
        }

        $result = $this->scannerService->scan($url);

        return response()->json($result);
    }

    // 🔥 GET: ambil hasil scan
    public function result($id)
    {
        $scan = Scan::with('results')->find($id);

        if (!$scan) {
            return response()->json([
                'error' => 'Scan not found'
            ], 404);
        }

        return response()->json($scan);
    }
}

