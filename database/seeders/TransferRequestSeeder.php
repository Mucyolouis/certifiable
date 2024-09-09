<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Church;
use App\Models\TransferRequest;
use Illuminate\Database\Seeder;

class TransferRequestSeeder extends Seeder
{
    public function run()
    {
        // Get all users with the role 'christian'
        $christians = User::where('role', 'christian')->get();

        // Get all churches
        $churches = Church::all();

        // Get all users with the role 'pastor'
        $pastors = User::where('role', 'pastor')->get();

        foreach ($christians as $christian) {
            // Create 1-3 transfer requests for each christian
            $numRequests = rand(1, 3);

            for ($i = 0; $i < $numRequests; $i++) {
                $fromChurch = $churches->random();
                $toChurch = $churches->except($fromChurch->id)->random();

                TransferRequest::create([
                    'christian_id' => $christian->id,
                    'from_church_id' => $fromChurch->id,
                    'to_church_id' => $toChurch->id,
                    'description' => $this->getRandomDescription(),
                    'approval_status' => $this->getRandomStatus(),
                    'approved_by' => $this->getRandomStatus() !== 'Pending' ? $pastors->random()->id : null,
                ]);
            }
        }
    }

    private function getRandomDescription()
    {
        $descriptions = [
            'Moving to a new city',
            'Closer to home',
            'Prefer the worship style',
            'Better youth programs',
            'More volunteer opportunities',
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function getRandomStatus()
    {
        $statuses = ['Pending', 'Approved', 'Rejected'];
        $weights = [70, 20, 10];

        return $this->getRandomWeightedElement($statuses, $weights);
    }

    private function getRandomWeightedElement($elements, $weights)
    {
        $totalWeight = array_sum($weights);
        $randomNumber = mt_rand(1, $totalWeight);

        foreach ($elements as $index => $element) {
            $randomNumber -= $weights[$index];
            if ($randomNumber <= 0) {
                return $element;
            }
        }
    }
}