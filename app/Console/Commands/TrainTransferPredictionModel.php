<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TransferPredictionService;
use Illuminate\Support\Facades\Log;

class TrainTransferPredictionModel extends Command
{
    protected $signature = 'model:train-transfer-prediction';
    protected $description = 'Train the transfer prediction model';

    public function handle()
    {
        try {
            $this->info('Starting transfer prediction model training...');
            $service = new TransferPredictionService();
            $service->train();
            $this->info('Transfer prediction model trained successfully.');
        } catch (\Exception $e) {
            Log::error('Error training transfer prediction model: ' . $e->getMessage());
            $this->error('Error training model: ' . $e->getMessage());
            $this->error('Check the Laravel log for more details.');
        }
    }
}