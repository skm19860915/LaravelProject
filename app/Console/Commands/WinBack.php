<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class WinBack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:winprocess';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'daily check Status for permanent delete';

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
     * @return mixed
     */
    public function handle()
    {

        //$next_day = date('d-m-Y', strtotime(date('Y-m-d') . '-3 months'));
        //DB::table('recurrings')->where('event_date',$current_date)->get();

        DB::table('case')->where( 'created_at', '==', Carbon::now()->subDays(90))->where('status',0)->delete();
        
        DB::table('firms')->where( 'created_at', '==', Carbon::now()->subDays(90))->where('status',0)->delete();
        
        DB::table('users')->where( 'created_at', '==', Carbon::now()->subDays(90))->where('status',0)->delete();
    }
}
