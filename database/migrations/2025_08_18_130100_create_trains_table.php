<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('train_route_id')->constrained('train_routes')->cascadeOnDelete();
            $table->string('name')->unique();
            $table->string('phone')->nullable();
            $table->text('details')->nullable();
            $table->string('map_one')->nullable();
            $table->string('map_two')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trains');
    }
};

