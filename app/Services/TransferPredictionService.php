<?php

namespace App\Services;

use App\Models\TransferRequest;
use Phpml\Classification\SVC;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Illuminate\Support\Facades\Log;

class TransferPredictionService
{
    protected $samples = [];
    protected $labels = [];
    protected $vocabulary = [];
    protected $isTrained = true;
    protected $model;
    protected $tfidf;
    protected $tokenizer;

    public function __construct()
        {
            $this->model = new SVC();
            $this->tfidf = new TfIdfTransformer();
            $this->tokenizer = new WhitespaceTokenizer();
            $this->vocabulary = [];
        }

    public function train()
        {
            $transferRequests = TransferRequest::with(['christian', 'fromChurch', 'toChurch'])->get();

            // Ensure $transferRequests is a valid collection, even if it's empty
            if ($transferRequests === null) {
                $transferRequests = collect();
            }
            $transferRequests = $transferRequests ?: collect();

            if ($transferRequests->isEmpty()) {
                throw new \Exception("No transfer requests found. Unable to train the model.");
            }

            Log::info("Number of transfer requests: " . $transferRequests->count());

            // First pass: build vocabulary
            foreach ($transferRequests as $request) {
                $features = $this->prepareFeatures($request);
                Log::debug("Prepared features for request {$request->id}: " . json_encode($features));
                $this->buildVocabulary($features);
            }

            Log::info("Vocabulary size: " . count($this->vocabulary));
            Log::debug("Vocabulary: " . json_encode($this->vocabulary));

            // Second pass: create samples
            foreach ($transferRequests as $request) {
                $tokenizedFeatures = $this->tokenizeFeatures($this->prepareFeatures($request));
                Log::debug("Tokenized features for request {$request->id}: " . json_encode($tokenizedFeatures));
                
                if (!empty(array_filter($tokenizedFeatures))) {
                    $this->samples[] = $tokenizedFeatures;
                    $this->labels[] = $request->approval_status;
                } else {
                    Log::warning("Empty features for request {$request->id}");
                }
            }

            Log::info("Number of samples after tokenization: " . count($this->samples));
            Log::info("Number of labels after tokenization: " . count($this->labels));

            if (empty($this->samples) || empty($this->labels)) {
                throw new \Exception("No valid samples or labels generated after tokenization.");
            }

            // Transform text data to TF-IDF features
            $this->tfidf->fit($this->samples);

            // Train the model
            try {
                $this->model->train($this->samples, $this->labels);
            } catch (\Exception $e) {
                Log::error("Error training model: " . $e->getMessage());
                throw new \Exception("Error training model: " . $e->getMessage());
            }

            $this->isTrained = true;
            Log::info("Model training completed successfully.");

            // Write the data to a CSV file
            $this->writeToCSV();
        }

    public function writeToCSV()
        {
            $csvData = [];
            $csvData[] = array_keys($this->vocabulary);

            foreach ($this->samples as $index => $sample) {
                $row = array_fill(0, count($this->vocabulary), 0);
                foreach ($sample as $featureIndex => $value) {
                    $row[$featureIndex] = $value;
                }
                $row[] = $this->labels[$index];
                $csvData[] = $row;
            }

            $csvFilePath = storage_path('app/transfer_prediction_data.csv');
            $file = fopen($csvFilePath, 'w');

            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }

            fclose($file);

            Log::info("Transfer prediction data saved to: " . $csvFilePath);
        }
    public function predict($request)
        {
            if (!$this->isTrained) {
                throw new \Exception("Model is not trained. Please train the model first.");
            }

            $features = $this->prepareFeatures($request);
            $tokenizedFeatures = $this->tokenizeFeatures($features);
            //$transformedFeatures = $this->tfidf->transform([$tokenizedFeatures]);

            return $this->model->predict($tokenizedFeatures)[1];
        }

    protected function prepareFeatures($request)
        {
            if ($request instanceof TransferRequest) {
                return [
                    'role' => $request->christian->role ?? '',
                    'from_church' => $request->fromChurch->name ?? '',
                    'to_church' => $request->toChurch->name ?? '',
                    'description' => $request->description ?? '',
                    'age' => $request->christian->age ?? '',
                    'gender' => $request->christian->gender ?? '',
                    // Add more relevant features here
                ];
            } elseif (is_array($request)) {
                return [
                    'role' => $request['christian']['role'] ?? '',
                    'from_church' => $request['from_church']['name'] ?? '',
                    'to_church' => $request['to_church']['name'] ?? '',
                    'description' => $request['description'] ?? '',
                    'age' => $request['christian']['age'] ?? '',
                    'gender' => $request['christian']['gender'] ?? '',
                    // Add more relevant features here
                ];
            } else {
                throw new \InvalidArgumentException("Invalid input type for prediction. Expected TransferRequest object or array.");
            }
        }

    protected function buildVocabulary(array $features)
        {
            foreach ($features as $key => $value) {
                $tokens = $this->tokenizer->tokenize($this->sanitizeString($value));
                foreach ($tokens as $token) {
                    $vocabularyKey = $key . '_' . $token;
                    if (!isset($this->vocabulary[$vocabularyKey])) {
                        $this->vocabulary[$vocabularyKey] = count($this->vocabulary);
                    }
                }
            }
        }

    protected function tokenizeFeatures(array $features)
        {
            $tokenizedFeatures = array_fill(0, count($this->vocabulary), 0);
            foreach ($features as $key => $value) {
                $tokens = $this->tokenizer->tokenize($this->sanitizeString($value));
                foreach ($tokens as $token) {
                    $vocabularyKey = $key . '_' . $token;
                    if (isset($this->vocabulary[$vocabularyKey])) {
                        $tokenizedFeatures[$this->vocabulary[$vocabularyKey]]++;
                    }
                }
            }
            return $tokenizedFeatures;
        }

    protected function sanitizeString($input)
        {
            if (!is_string($input)) {
                return '';
            }
            // Convert to lowercase and remove any character that's not alphanumeric, whitespace, or common punctuation
            return preg_replace('/[^a-z0-9\s\.\,\-]/', '', strtolower($input));
        }

    public function getTransferRequests()
        {
            return TransferRequest::with(['christian', 'fromChurch', 'toChurch'])->get();
        }
    // public function predictPercentageOfTransfers()
    //     {
    //         if (!$this->isTrained) {
    //             throw new \Exception("Model is not trained. Please train the model first.");
    //         }
    
    //         $transferRequests = $this->getTransferRequests();
    //         $totalPredictions = count($transferRequests);
    //         $predictedTransfers = 0;
    
    //         foreach ($transferRequests as $request) {
    //             $features = $this->prepareFeatures($request);
    //             $tokenizedFeatures = $this->tokenizeFeatures($features);
    //             $prediction = $this->model->predict($tokenizedFeatures);
                
    //             if ($prediction == 'approved') {
    //                 $predictedTransfers++;
    //             }
    //         }
    
    //         $percentagePredicted = ($predictedTransfers / $totalPredictions) * 100;
    //         return round($percentagePredicted, 2);
    //     }
    
    public function predictPercentageOfTransfers()
    {
        if (!$this->isTrained) {
            Log::warning('Transfer prediction model is not trained.');
            throw new \Exception("Model is not trained. Please train the model first.");
        }

        $transferRequests = $this->getTransferRequests();
        $totalPredictions = count($transferRequests);

        if ($totalPredictions === 0) {
            Log::warning('No transfer requests found for prediction.');
            throw new \Exception("No transfer requests available for prediction.");
        }

        Log::info("Predicting transfers for {$totalPredictions} requests.");

        $predictedTransfers = 0;

        foreach ($transferRequests as $request) {
            try {
                $features = $this->prepareFeatures($request);
                $tokenizedFeatures = $this->tokenizeFeatures($features);
                
                if (empty($tokenizedFeatures)) {
                    Log::warning("Empty features for request ID: {$request->id}");
                    continue;
                }

                $prediction = $this->model->predict($tokenizedFeatures);
                
                if ($prediction == 'approved') {
                    $predictedTransfers++;
                }
            } catch (\Exception $e) {
                Log::error("Error predicting for request ID {$request->id}: " . $e->getMessage());
            }
        }

        if ($predictedTransfers === 0) {
            Log::warning('No transfers were predicted as approved.');
        }

        $percentagePredicted = ($predictedTransfers / $totalPredictions) * 100;
        Log::info("Predicted {$percentagePredicted}% of transfers.");

        return round($percentagePredicted, 2);
    }
}