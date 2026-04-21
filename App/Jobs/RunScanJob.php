<?php

namespace App\Jobs;

use App\Models\Scan;
use App\Models\Result;
use App\Models\Vulnerability;
use App\Services\ScannerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RunScanJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $scanId) {}

    public function handle(ScannerService $scannerService): void
    {
        $scan = Scan::findOrFail($this->scanId);

        $scan->update([
            'status' => 'scanning',
            'started_at' => now(),
        ]);

        try {
            $findings = $scannerService->scan($scan->target_url);

            foreach ($findings as $f) {

                // 🔥 simpan ke results
                $result = Result::create([
                    'scan_id' => $scan->id,
                    'type' => $f['type'],
                    'is_vulnerable' => true,
                    'payload' => $f['payload'] ?? null,
                ]);

                // 🔥 simpan vulnerability
                Vulnerability::create([
                    'result_id' => $result->id,
                    'name' => $f['type'],
                    'severity' => $f['severity'],
                    'description' => $f['description'],
                ]);
            }

            $scan->update([
                'status' => 'done',
                'finished_at' => now(),
            ]);

        } catch (\Throwable $e) {
            $scan->update([
                'status' => 'failed',
                'finished_at' => now(),
            ]);
        }
    }
}
