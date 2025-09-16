<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('upazila_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('type')->default('tutor'); // tutor or tuition
            $table->string('class')->nullable();
            $table->string('gender')->nullable();
            $table->string('subject')->nullable();
            $table->unsignedTinyInteger('days_per_week')->nullable();
            $table->unsignedInteger('salary')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('map')->nullable();
            $table->text('details')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutors');
    }
};

