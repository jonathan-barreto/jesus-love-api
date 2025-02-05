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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('phone_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('accepted_terms')->default(false);
            $table->boolean('is_subscriber')->default(false);
            $table->boolean('visibility')->default(true);
            $table->timestamp('last_login')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn([
                'phone_number', 
                'date_of_birth', 
                'gender', 
                'bio', 
                'is_active', 
                'accepted_terms', 
                'is_subscriber', 
                'visibility', 
                'last_login'
            ]);
        });
    }
};
