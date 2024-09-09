<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChurchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            ['id' => 1, 'name' => 'BASE', 'parish_id' =>1],
            ['id' => 2, 'name' => 'BUHANDE', 'parish_id' =>1],
            ['id' => 3, 'name' => 'BURAMIRA', 'parish_id' =>1],
            ['id' => 4, 'name' => 'RUSHOKI', 'parish_id' =>1],
            ['id' => 5, 'name' => 'BUYOGOMA', 'parish_id' =>1],
            ['id' => 6, 'name' => 'CYIRI', 'parish_id' =>1],
            ['id' => 7, 'name' => 'GASIZA', 'parish_id' =>1],
            ['id' => 8, 'name' => 'GATETE', 'parish_id' =>1],
            ['id' => 9, 'name' => 'GIHINGA', 'parish_id' =>1],
            ['id' => 10, 'name' => 'GITARE', 'parish_id' =>1],
            ['id' => 11, 'name' => 'KARAMBO', 'parish_id' =>1],
            ['id' => 12, 'name' => 'KARUHURA', 'parish_id' =>1],
            ['id' => 13, 'name' => 'KIRURI', 'parish_id' =>1],
            ['id' => 14, 'name' => 'MAREMBO', 'parish_id' =>1],
            ['id' => 15, 'name' => 'MURAMBI', 'parish_id' =>1],
            ['id' => 16, 'name' => 'NYANGE', 'parish_id' =>1],
            ['id' => 17, 'name' => 'NYIRANGARAMA', 'parish_id' =>1],
            ['id' => 18, 'name' => 'REBERO BETERI', 'parish_id' =>1],
            ['id' => 19, 'name' => 'RUGARAMA', 'parish_id' =>1],
            ['id' => 20, 'name' => 'RUKORE', 'parish_id' =>1],
            ['id' => 21, 'name' => 'RUSURA', 'parish_id' =>1],
            ['id' => 22, 'name' => 'RUVUMBA', 'parish_id' =>1],
            ['id' => 23, 'name' => 'TARE', 'parish_id' =>1],
            ['id' => 24, 'name' => 'TERAMBERE', 'parish_id' =>1],
        ];
        foreach ($data as $item) {
            \App\Models\Church::create($item);
        }
    
    }
}
