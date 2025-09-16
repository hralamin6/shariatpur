<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicemen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('serviceman_type_id')->constrained('serviceman_types')->cascadeOnDelete();
            $table->foreignId('upazila_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('service_title')->nullable();
            $table->unsignedTinyInteger('experience_years')->default(0);
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->text('details')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicemen');
    }
};

