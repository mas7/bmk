<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contractor_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id');
            $table->foreignId('service_id');
            $table->foreignId('service_category_id');
            $table->integer('price')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contractor_services');
    }
};
