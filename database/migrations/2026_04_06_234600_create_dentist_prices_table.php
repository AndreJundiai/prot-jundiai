<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dentist_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dentist_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->timestamps();
            
            // Ensure one price per service per dentist
            $table->unique(['dentist_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dentist_prices');
    }
};
