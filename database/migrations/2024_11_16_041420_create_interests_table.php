<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('interests', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interests');
    }
};
