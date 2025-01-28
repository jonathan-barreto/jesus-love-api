<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DenominationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $denominations = [
            ['name' => 'Católica'],
            ['name' => 'Evangélica'],
            ['name' => 'Batista'],
            ['name' => 'Adventista'],
            ['name' => 'Presbiteriana'],
            ['name' => 'Metodista'],
            ['name' => 'Assembleia de Deus'],
            ['name' => 'Congregação Cristã no Brasil'],
            ['name' => 'Deus é Amor'],
            ['name' => 'Quadrangular'],
            ['name' => 'Universal do Reino de Deus'],
            ['name' => 'Church'],
        ];

        DB::table('denominations')->insert($denominations);
    }
}
