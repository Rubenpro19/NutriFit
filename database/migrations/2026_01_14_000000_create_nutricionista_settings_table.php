<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nutricionista_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('consultation_price', 8, 2)->default(30.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nutricionista_settings');
    }
};
