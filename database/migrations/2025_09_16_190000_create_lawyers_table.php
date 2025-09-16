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
        Schema::create('lawyers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('upazila_id');
            $table->string('name', 150);
            $table->string('designation', 150)->nullable();
            $table->string('thana', 150)->nullable(); // Chamber/Court name
            $table->string('address', 500)->nullable();
            $table->string('map', 1000)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('alt_phone', 30)->nullable();
            $table->text('details')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('upazila_id')->references('id')->on('upazilas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawyers');
    }
};

