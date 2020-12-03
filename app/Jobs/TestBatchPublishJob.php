<?php

namespace App\Jobs;

use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class TestBatchPublishJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $prodOid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $prodOid)
    {
        //
        $this->prodOid = $prodOid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if ($this->prodOid >= 10000) {
            dump('all down');
            return;
        }
        $prodOids = range($this->prodOid + 1, $this->prodOid + 500);
        $newProdOid = $this->prodOid + 500;
        Bus::batch(collect($prodOids)->map(function ($value) {
            return new TestJob($value);
        }))->then(function () {
            dump('結束0');
        })->catch(function (Batch $batch, \Throwable $e) {
            Log::error('test', ['exception' => $e]);
        })->finally(function () use ($newProdOid) {
            dump('結束');
            TestBatchPublishJob::dispatch($newProdOid)->delay(2);
        })->dispatch();
    }
}
