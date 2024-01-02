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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();

            $table->integer('basicSalary');
            $table->integer('rareCost');
            $table->integer('bonus');
            $table->integer('attendedBonus');
            $table->integer('busFee');
            $table->integer('FirstTotal');

            $table->integer('mealDeduct');
            $table->integer('absence');
            $table->integer('ssbFee');
            $table->integer('fine');
            $table->integer('redeem');
            $table->integer('advance_salary');
            $table->string('otherDeductLable')->nullable();
            $table->integer('otherDeduct');
            $table->integer('staff_id');
            $table->integer('reservation_id');
            $table->integer('finalTotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
