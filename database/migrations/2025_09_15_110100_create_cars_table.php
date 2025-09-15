<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('upazila_id')->constrained()->cascadeOnDelete();
            $table->string('name')->unique();
            $table->string('driver_name')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('ac')->default(false);
            $table->unsignedInteger('seat_number')->nullable();
            $table->unsignedInteger('weight_capacity')->nullable();
            $table->text('address')->nullable();
            $table->text('map')->nullable();
            $table->unsignedInteger('rent_price')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
