<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('house_type_id')->constrained('house_types')->cascadeOnDelete();
            $table->foreignId('upazila_id')->constrained()->cascadeOnDelete();
            $table->string('name')->unique();
            $table->string('phone')->nullable();
            $table->text('details')->nullable();
            $table->text('map_one')->nullable();
            $table->string('area')->nullable();
            $table->unsignedSmallInteger('room_number')->default(0);
            $table->unsignedSmallInteger('bathroom_number')->default(0);
            $table->string('address')->nullable();
            $table->unsignedBigInteger('rent_price')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};

