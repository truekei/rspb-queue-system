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
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // R001 / W001
            $table->enum('type', ['R', 'W']);   // reservasi atau walk-in
            $table->dateTime('datetime');       // waktu mendaftar
            $table->enum('status', ['waiting', 'called', 'done'])->default('waiting');
            $table->foreignId('staff_id')->nullable()->constrained('staff')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
