<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('news_category_id')->constrained('news_categories')->cascadeOnDelete();
            $table->foreignId('upazila_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('image')->nullable();
            $table->string('status')->default('active');
            $table->unsignedBigInteger('views')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

