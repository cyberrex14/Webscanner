<?php

namespace App\Jobs;

use App\Models\Scan;
use App\Services\ScannerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunScanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $scanId;

    public function __construct(int $scanId)
    {
        $this->scanId = $scanId;
    }

    public function handle(ScannerService $scannerService): void
    {
        $scan = Scan::find($this->scanId);

        if (!$scan) {
            return;
        }

        $scan->update([
            'status' => 'scanning',
            'started_at' => now(),
        ]);

        try {
            $results = $scannerService->scan($scan->target_url);

            foreach ($results as $r) {
                $scan->results()->create($r);
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

            \Log::error($e->getMessage());
        }
    }
}
