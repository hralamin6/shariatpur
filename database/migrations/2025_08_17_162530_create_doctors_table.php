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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('qualification')->nullable();
            $table->string('current_position')->nullable();
            $table->string('chamber_one')->nullable();
            $table->string('chamber_two')->nullable();
            $table->string('chamber_three')->nullable();
            $table->string('chamber_one_phone')->nullable();
            $table->string('chamber_two_phone')->nullable();
            $table->string('chamber_three_phone')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
