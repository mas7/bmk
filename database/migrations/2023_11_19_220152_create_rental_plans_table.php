<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rental_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id');
            $table->foreignId('client_id')->constrained('users', 'id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('monthly_rent');
            $table->unsignedTinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_plans');
    }
};
