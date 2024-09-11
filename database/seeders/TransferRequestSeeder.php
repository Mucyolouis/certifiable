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
        $christians = User::role('christian')->get();

        // Get all churches
        $churches = Church::all();

        // Get all users with the role 'pastor'
        $pastors = User::role('pastor')->get();

        // Check if we have the necessary data
        if ($christians->isEmpty()) {
            $this->command->info('No users with "christian" role found. Skipping TransferRequest seeding.');
            return;
        }

        if ($churches->count() < 2) {
            $this->command->info('Not enough churches found. At least 2 churches are required. Skipping TransferRequest seeding.');
            return;
        }

        if ($pastors->isEmpty()) {
            $this->command->info('No users with "pastor" role found. Skipping TransferRequest seeding.');
            return;
        }

        foreach ($christians as $christian) {
            for ($i = 0; $i < 2; $i++) {
                $fromChurch = $churches->random();
                $toChurch = $churches->except($fromChurch->id)->random();
                $status = $this->getRandomStatus();

                TransferRequest::create([
                    'christian_id' => $christian->id,
                    'from_church_id' => $fromChurch->id,
                    'to_church_id' => $toChurch->id,
                    'reason' => $this->getRandomReason(),
                    'description' => $this->getRandomDescription(),
                    'approval_status' => $status,
                    'approved_by' => $status !== 'Pending' ? $pastors->random()->id : null,
                ]);
            }
        }
    }

    private function getRandomDescription()
    {
        $descriptions = [
            'Geographical Relocation',
            'Theological Differences',
            'Family Reasons',
            'Work',
            'Church Leadership and Management',
            'Closer to home',
            'Prefer the worship style',
            'Better youth programs',
            'More volunteer opportunities',
            'Other',
            
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function getRandomReason()
    {
        $reasons = [
            'Geographical Relocation',
            'Theological Differences',
            'Family Reasons',
            'Work',
            'Church Leadership and Management',
            'Closer to home',
            'Prefer the worship style',
            'Better youth programs',
            'More volunteer opportunities',
            'Other',
            
        ];

        return $reasons[array_rand($reasons)];
    }

    private function getRandomStatus()
    {
        $statuses = ['Pending', 'Approved', 'Rejected'];
        $weights = [80, 10, 10];

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