<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScanRequest;
use App\Jobs\RunScanJob;
use App\Models\Scan;

class ScanController extends Controller
{
    // 🔥 START SCAN
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

    // 🔥 GET RESULT (SUDAH FIX)
    public function result($id)
    {
        $scan = Scan::with('results.vulnerabilities')->find($id);

        if (!$scan) {
            return response()->json([
                'error' => 'Scan not found'
            ], 404);
        }

        // 🔥 NORMALISASI DI BACKEND (PENTING)
        $results = $scan->results->flatMap(function ($r) {
            return $r->vulnerabilities->map(function ($v) use ($r) {
                return [
                    'type' => $r->type,
                    'severity' => $v->severity,
                    'description' => $v->description,
                ];
            });
        });

        return response()->json([
            'id' => $scan->id,
            'status' => $scan->status,
            'results' => $results,
        ]);
    }
}
