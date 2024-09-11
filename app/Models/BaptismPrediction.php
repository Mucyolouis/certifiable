<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Phpml\Classification\SVC;
use Phpml\Classification\NaiveBayes;
use Phpml\SupportVectorMachine\Kernel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BaptismPrediction extends Model
{
    private static function prepareData()
    {
        $users = User::all(['id', 'baptized', 'baptized_at', 'date_of_birth'])->toArray();
        
        $samples = [];
        $labels = [];
        
        foreach ($users as $user) {
            if (empty($user['date_of_birth'])) {
                Log::warning("User ID {$user['id']} is missing date_of_birth");
                continue;
            }

            $dateOfBirth = Carbon::parse($user['date_of_birth']);
            $baptizedAge = !empty($user['baptized_at']) 
                ? Carbon::parse($user['baptized_at'])->diffInYears($dateOfBirth)
                : 0;
            $currentAge = Carbon::now()->diffInYears($dateOfBirth);
            
            if ($currentAge < 0 || $baptizedAge < 0 || $baptizedAge > $currentAge) {
                Log::warning("Invalid age data for User ID {$user['id']}");
                continue;
            }

            $samples[] = [$baptizedAge, $currentAge];
            $labels[] = isset($user['baptized']) ? (int)$user['baptized'] : 0;
        }
        
        Log::info("Total valid samples: " . count($samples));
        return [$samples, $labels];
    }

    private static function manualNormalize($samples)
    {
        $normalized = [];
        foreach ($samples as $sample) {
            $sum = array_sum(array_map('abs', $sample));
            $normalized[] = $sum > 0 ? array_map(function($val) use ($sum) { 
                return $val / $sum; 
            }, $sample) : $sample;
        }
        return $normalized;
    }

    public static function trainModel()
    {
        try {
            [$samples, $labels] = self::prepareData();

            if (count($samples) < 2) {
                throw new \Exception("Insufficient training data");
            }

            Log::info('Samples prepared: ' . count($samples));

            // Manual normalization
            $normalizedSamples = self::manualNormalize($samples);
            Log::info('Samples manually normalized');

            try {
                $classifier = new SVC(Kernel::RBF, $cost = 1000);
                $classifier->train($normalizedSamples, $labels);
            } catch (\Exception $e) {
                Log::warning('SVC training failed, falling back to Naive Bayes: ' . $e->getMessage());
                $classifier = new NaiveBayes();
                $classifier->train($samples, $labels); // Use original samples for Naive Bayes
            }

            $modelData = [
                'classifier' => $classifier,
                'normalizer' => 'manual'
            ];
            $modelPath = storage_path('app/baptism.csv');
            file_put_contents($modelPath, serialize($modelData));

            Log::info('Model trained and saved successfully');
            return true;
        } catch (\Exception $e) {
            Log::error('Error training baptism prediction model: ' . $e->getMessage());
            return false;
        }
    }

    public static function predict($dateOfBirth)
{
    try {
        $modelPath = storage_path('app/baptism_prediction_model.php');
        if (!file_exists($modelPath)) {
            throw new \Exception("Model file not found. Please train the model first.");
        }

        $modelData = unserialize(file_get_contents($modelPath));
        $classifier = $modelData['classifier'];

        $currentAge = Carbon::now()->diffInYears($dateOfBirth);
        $baptizedAge = 0; // Since we're predicting for unbaptized users

        $sample = [$baptizedAge, $currentAge];
        
        if ($modelData['normalizer'] === 'manual') {
            $sample = self::manualNormalize([$sample])[0];
        }
        
        $prediction = $classifier->predict([$sample]);
        
        return $prediction[0];
    } catch (\Exception $e) {
        \Log::error('Error predicting baptism likelihood: ' . $e->getMessage());
        return null;
    }
}
}