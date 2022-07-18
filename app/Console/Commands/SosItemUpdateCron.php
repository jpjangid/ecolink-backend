<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Traits\SosItemUpdate;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\api\OrderController;

class SosItemUpdateCron extends Command
{
    use SosItemUpdate;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sositemupdate:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Product Information from SOS';

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
        $data = $this->itemUpdate();
        \Log::info("Cron Job is working Fine");
    }
}
