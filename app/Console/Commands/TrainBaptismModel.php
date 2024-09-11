<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BaptismPrediction;

class TrainBaptismModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:train-baptism-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        BaptismPrediction::trainModel();
    }
}
