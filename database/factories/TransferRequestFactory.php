<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Church;
use App\Models\TransferRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransferRequest>
 */
class TransferRequestFactory extends Factory
{

    //$transferRequests = TransferRequest::factory()->count(10)->create();
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'christian_id' => User::factory(),
            'from_church_id' => Church::factory(),
            'to_church_id' => Church::factory(),
            'description' => $this->faker->paragraph,
            'approval_status' => $this->faker->randomElement(['Pending', 'Approved', 'Rejected']),
            'approved_by' => $this->faker->randomElement([null, User::factory()]),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'deleted_at' => $this->faker->optional(0.1)->dateTimeBetween('-1 year', 'now'),
            'reason' => $this->faker->randomElement([
                'Geographical Relocation',
                'Theological Differences',
                'Family Reasons',
                'Work',
                'Church Leadership and Management',
                'Other'
            ]),
        ];
    }
}
