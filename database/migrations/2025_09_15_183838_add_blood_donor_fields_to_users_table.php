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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_blood_donor')->default(false);
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->date('last_donate_date')->nullable();
            $table->text('donor_details')->nullable();
            $table->unsignedBigInteger('upazila_id')->nullable();
            $table->string('donor_status')->default('available'); // available, unavailable, inactive
            $table->integer('total_donations')->default(0);

            $table->foreign('upazila_id')->references('id')->on('upazilas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['upazila_id']);
            $table->dropColumn([
                'is_blood_donor',
                'blood_group',
                'last_donate_date',
                'donor_details',
                'upazila_id',
                'donor_status',
                'total_donations'
            ]);
        });
    }
};
