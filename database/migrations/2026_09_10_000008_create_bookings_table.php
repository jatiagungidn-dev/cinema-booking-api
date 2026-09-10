<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('showtime_id')->constrained()->restrictOnDelete();
            $table->string('status', 20)->default('PENDING');
            $table->unsignedInteger('total_amount')->default(0);
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
            $table->index(['showtime_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
