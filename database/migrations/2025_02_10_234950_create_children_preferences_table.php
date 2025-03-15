<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('children_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Inserindo as opções de filhos padrões
        DB::table('children_preferences')->insert([
            ['name' => 'Sem filhos e não desejo ter'],
            ['name' => 'Sem filhos e desejo ter'],
            ['name' => 'Com filhos e não desejo ter mais filhos'],
            ['name' => 'Com filhos e desejo ter mais filhos']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children_preferences');
    }
};
