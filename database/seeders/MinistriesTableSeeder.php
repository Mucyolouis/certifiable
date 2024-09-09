<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MinistriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ministries = [
            [
                'name' => 'Worship Ministry',
                'description' => 'Involves activities like singing in the choir, leading worship, and playing musical instruments.',
            ],
            [
                'name' => 'Praise and Worship Team',
                'description' => 'Specifically refers to the group that leads the congregation in singing and praise.',
            ],
            [
                'name' => 'Deacon Ministry',
                'description' => 'Involves serving in a deacon role, which typically includes helping with church administration, providing care to members, and supporting the pastoral staff.',
            ],
            [
                'name' => 'Volunteer Ministries',
                'description' => 'General term for various forms of voluntary service within the church.',
            ],
            [
                'name' => 'Service Ministries',
                'description' => 'Includes roles like ushers, greeters, and those involved in organizing church events and services.',
            ],
        ];

        foreach ($ministries as $ministry) {
            DB::table('ministries')->insert([
                'name' => $ministry['name'],
                'description' => $ministry['description'],
            ]);
        }
    }
}