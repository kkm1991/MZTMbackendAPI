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
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->string('nrc')->nullable();
            $table->date('dob')->nullable();
            $table->date('start_working_date');
            $table->integer('educationID');
            $table->integer('depID');
            $table->integer('positionID');
            $table->integer('basic_salary');
            $table->integer('debt')->nullable();
            $table->longText('address')->nullable();
            $table->longText('image')->nullable();
            $table->boolean('active_status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
