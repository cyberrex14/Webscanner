<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScanRequest;
use App\Jobs\RunScanJob;
use App\Models\Scan;

class ScanController extends Controller
{
    public function store(ScanRequest $request)
    {
        $scan = Scan::create([
            'user_id' => 1, // sementara
            'target_url' => $request->validated('target_url'),
            'status' => 'pending',
        ]);

        RunScanJob::dispatch($scan->id);

        return response()->json([
            'message' => 'Scan queued',
            'scan_id' => $scan->id
        ], 201);
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
