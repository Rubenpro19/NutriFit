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
        Schema::create('attention_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attention_id')->constrained('attentions')->onDelete('cascade');
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 4, 2)->nullable();
            $table->decimal('bmi', 5, 2)->nullable();
            $table->decimal('waist', 5, 2)->nullable();
            $table->decimal('hip', 5, 2)->nullable();
            $table->decimal('body_fat', 5, 2)->nullable();
            $table->string('blood_pressure', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attention_data');
    }
};
