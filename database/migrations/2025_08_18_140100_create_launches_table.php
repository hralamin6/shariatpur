<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('launches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('launch_route_id')->constrained('launch_routes')->cascadeOnDelete();
            $table->string('name')->unique();
            $table->string('phone')->nullable();
            $table->text('details')->nullable();
            $table->text('map_one')->nullable();
            $table->text('map_two')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('launches');
    }
};

