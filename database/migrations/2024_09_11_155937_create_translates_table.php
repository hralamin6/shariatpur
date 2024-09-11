<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('translates', function (Blueprint $table) {
            $table->collation = 'utf8mb4_bin';  // Set collation
            $table->bigIncrements('id');        // Primary key
            $table->integer('status')->default(0);  // Status field
            $table->string('locale');           // Language locale (e.g., 'en', 'fr')
            $table->string('group');            // Group of translations (e.g., 'messages', 'validation')
            $table->text('key');                // Translation key (e.g., 'welcome', 'validation.email')
            $table->text('value')->nullable();  // Translation value (actual translation)
            $table->timestamps();               // Created at and updated at timestamps
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translates');
    }
};
