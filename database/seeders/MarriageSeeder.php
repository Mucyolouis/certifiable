<?php

namespace Database\Seeders;

use App\Models\Marriage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MarriageSeeder extends Seeder
{
    public function run(): void
    {
        // Get some existing users or create them if needed
        $users = User::take(10)->get();
        if ($users->count() < 10) {
            // Create some test users if needed
            while ($users->count() < 10) {
                $users->push(User::factory()->create());
            }
        }

        // Create historical marriage data for the past 24 months
        $startDate = Carbon::now()->subMonths(24)->startOfMonth();
        
        // Pattern: More marriages in summer months, fewer in winter
        $monthlyPatterns = [
            1 => 3,  // January
            2 => 2,  // February
            3 => 4,  // March
            4 => 5,  // April
            5 => 7,  // May
            6 => 10, // June
            7 => 12, // July
            8 => 10, // August
            9 => 8,  // September
            10 => 6, // October
            11 => 4, // November
            12 => 5  // December
        ];

        for ($i = 0; $i < 24; $i++) {
            $currentDate = $startDate->copy()->addMonths($i);
            $baseCount = $monthlyPatterns[$currentDate->month];
            
            // Add some random variation
            $count = rand($baseCount - 1, $baseCount + 2);
            
            for ($j = 0; $j < $count; $j++) {
                $spouse1 = $users->random();
                $spouse2 = $users->except($spouse1->id)->random();
                $officiant = $users->except([$spouse1->id, $spouse2->id])->random();

                Marriage::create([
                    'spouse1_id' => $spouse1->id,
                    'spouse2_id' => $spouse2->id,
                    'officiated_by' => $officiant->id,
                    'marriage_date' => $currentDate->copy()->addDays(rand(1, 28)),
                ]);
            }
        }
    }
}