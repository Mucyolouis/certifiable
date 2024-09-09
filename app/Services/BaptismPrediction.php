<?php

use App\Models\User;
use Phpml\Metric\Accuracy;
use Phpml\Dataset\ArrayDataset;
use Phpml\Classification\Ensemble\RandomForest;
use Phpml\CrossValidation\StratifiedRandomSplit;

class BaptismPrediction
{
    private $model;

    public function __construct()
    {
        $this->model = new RandomForest();
    }

    public function prepareData()
    {
        $users = User::select(
            'date_of_birth',
            'church_id',
            'ministry_id',
            'active_status',
            'marital_status',
            'baptized'
        )->get();

        $samples = [];
        $labels = [];

        foreach ($users as $user) {
            $samples[] = [
                $this->calculateAge($user->date_of_birth),
                $user->church_id,
                $user->ministry_id,
                $user->active_status,
                $this->encodeMaritalStatus($user->marital_status)
            ];
            $labels[] = $user->baptized ? 1 : 0;
        }

        return new ArrayDataset($samples, $labels);
    }

    private function calculateAge($dateOfBirth)
    {
        return \Carbon\Carbon::parse($dateOfBirth)->age;
    }

    private function encodeMaritalStatus($status)
    {
        $statusMap = ['single' => 0, 'married' => 1];
        return $statusMap[$status] ?? -1;
    }

    public function trainModel(ArrayDataset $dataset)
    {
        $split = new StratifiedRandomSplit($dataset, 0.2);
        $this->model->train($split->getTrainSamples(), $split->getTrainLabels());

        // Evaluate the model
        $predictions = $this->model->predict($split->getTestSamples());
        $accuracy = Accuracy::score($split->getTestLabels(), $predictions);

        echo "Model accuracy: " . $accuracy . "\n";
    }

    public function predict(array $userData)
    {
        return $this->model->predict([$userData]);
    }
}

// Usage
$predictor = new BaptismPrediction();
$dataset = $predictor->prepareData();
$predictor->trainModel($dataset);

// Example prediction
$newUser = [30, 1, 2, 1, 0]; // Age: 30, Church ID: 1, Ministry ID: 2, Active: Yes, Single
$prediction = $predictor->predict($newUser);
echo "Prediction for new christian: " . ($prediction[0] ? "Likely" : "Unlikely") . " to be baptized\n";