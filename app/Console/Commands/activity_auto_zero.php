<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class activity_auto_zero extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity:zero';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $count = 0;
		echo "ok";
		//ActivityUserModel::where('counts', '>', $count)->update(array('counts'=>0));
    }
}
