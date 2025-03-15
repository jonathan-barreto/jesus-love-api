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
        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Inserindo os níveis de escolaridade padrões
        DB::table('educations')->insert([
            ['name' => 'Ensino Fundamental'],
            ['name' => 'Ensino Médio'],
            ['name' => 'Técnico/Profissionalizante'],
            ['name' => 'Superior Incompleto'],
            ['name' => 'Superior Completo'],
            ['name' => 'Pós Graduação/MBA'],
            ['name' => 'PhD/Mestrado']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educations');
    }
};
