<?php

namespace App\Console\Commands;

use App\Jobs\TestBatchPublishJob;
use Illuminate\Console\Command;

class TmpTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmp:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $prodOid = 0;
        TestBatchPublishJob::dispatch($prodOid);
        return 0;
    }

//    public static function batch(array $prodOidsList): void
//    {
//        $prodOids = array_shift($prodOidsList);
//        $bus = Bus::batch(collect($prodOids)->map(function ($value) {
//            return new TestJob($value);
//        }))->then(function () use ($prodOidsList){
//            dump('結束');
//            sleep(2);
//            self::batch($prodOidsList);
//        })->dispatch();
//    }
}
