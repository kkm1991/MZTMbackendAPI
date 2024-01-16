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
        Schema::create('monthly_reservations', function (Blueprint $table) {
            $table->id();

            $table->integer('rareCost')->default(0);
            $table->integer('bonus')->default(0);
            $table->integer('attendedBonus')->default(0);
            $table->integer('busFee')->default(0);
            $table->integer('mealDeduct')->default(0);
            $table->integer('absence')->default(0);
            $table->integer('ssbFee')->default(0);
            $table->integer('fine')->default(0);
            $table->integer('redeem')->default(0);
            $table->integer('advance_salary')->default(0);
            $table->string('otherDeductLable')->nullable();
            $table->integer('otherDeduct')->default(0);
            $table->integer('staff_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_reservations');
    }
};
