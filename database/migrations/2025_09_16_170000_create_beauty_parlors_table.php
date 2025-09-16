<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beauty_parlors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('upazila_id')->constrained()->cascadeOnDelete();
            $table->string('name')->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('map')->nullable();
            $table->text('details')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_parlors');
    }
};

