<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaxExemptCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taxexempt:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This cron is for deactivate user if its tax exempt validity date is expired.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = date('Y-m-d');
        $clients = DB::table('users')->where(['role_id' => 2, 'flag' => 0, 'tax_exempt' => 1])->where('validity_date','<=', $date)->get();

        foreach ($clients as $client){
            User::where('id', $client->id)->update([
                'tax_exempt'    =>  0,
            ]);
        }

        Log::info("Tax Exempt Cron Job is working Fine");
    }
}
