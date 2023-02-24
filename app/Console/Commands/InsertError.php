<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\Exception;

class InsertError extends Command
{
    protected $signature = 'insert:error';

    protected $description = 'Test command for CRON job';

    public function __construct()
    {
        parent::__construct();

    }

    public function handle()
    {
        try {
            DB::table('error')->insert([
                'user_id' => 1,
                'env' => 'test',
                'code' => 0,
                'file' => 'test',
                'line' => 1,
                'message' => 'Testing CRON job',
                'trace' => 'Testing CRON job',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }catch (Exception $e){
            echo $e->getMessage();
            return $e->getMessage();
        }
        return 0;
    }
}
