<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users', 'id');
            $table->foreignId('rental_plan_id')->constrained();
            $table->integer('amount');
            $table->integer('paid_amount');
            $table->dateTime('payment_date');
            $table->unsignedTinyInteger('status');
            $table->timestamps();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('rental_plan_id')->constrained('payments');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
