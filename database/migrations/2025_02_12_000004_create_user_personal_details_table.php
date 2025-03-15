<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_personal_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Substituindo ENUMs por foreign keys
            $table->foreignId('marital_status_id')->nullable()->constrained('marital_statuses')->onDelete('set null');
            $table->foreignId('children_preference_id')->nullable()->constrained('children_preferences')->onDelete('set null');
            $table->foreignId('education_id')->nullable()->constrained('educations')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_personal_details');
    }
};
