<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('interests')->insert([
            ['name' => 'Música'],
            ['name' => 'Esportes'],
            ['name' => 'Viagens'],
            ['name' => 'Tecnologia'],
            ['name' => 'Fotografia'],
            ['name' => 'Leitura'],
            ['name' => 'Culinária'],
            ['name' => 'Caminhadas'],
            ['name' => 'Cinema'],
            ['name' => 'Arte'],
            ['name' => 'Jogos'],
            ['name' => 'Desenvolvimento pessoal'],
            ['name' => 'Cultura'],
            ['name' => 'História'],
            ['name' => 'Voluntariado'],
            ['name' => 'Animais'],
            ['name' => 'Fitness'],
            ['name' => 'Moda'],
            ['name' => 'Beleza'],
            ['name' => 'Saúde'],
            ['name' => 'Política'],
            ['name' => 'Negócios'],
        ]);
    }
}
