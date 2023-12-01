<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('service_category_id')->constrained();
            $table->foreignId('property_id')->constrained();
            $table->foreignId('contractor_id')->nullable()->references('id')->on('users')->constrained();
            $table->text('description')->nullable();
            $table->text('contractor_description')->nullable();
            $table->tinyInteger('status');
            $table->dateTime('expected_visit_at')->nullable();
            $table->dateTime('resolution_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
