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
        Schema::create('marital_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Inserindo os estados civis padrões
        DB::table('marital_statuses')->insert([
            ['name' => 'Solteiro(a)'],
            ['name' => 'Viúvo(a)'],
            ['name' => 'Divorciado(a)']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marital_statuses');
    }
};
