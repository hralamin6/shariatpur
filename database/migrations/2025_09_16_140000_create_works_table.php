<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('upazila_id')->constrained()->cascadeOnDelete();
            $table->string('title'); // Work title
            $table->string('institution_name')->nullable();
            $table->string('designation')->nullable();
            $table->unsignedSmallInteger('posts_count')->default(0);
            $table->text('educational_qualification')->nullable();
            $table->string('experience')->nullable();
            $table->string('salary')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('last_date')->nullable();
            $table->text('address')->nullable();
            $table->string('application_link')->nullable();
            $table->text('details')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};

