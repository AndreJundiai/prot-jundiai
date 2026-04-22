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
        Schema::table('technical_records', function (Blueprint $table) {
            $table->string('escala')->nullable()->after('color');
            $table->string('antagonista')->nullable()->after('escala');
            $table->string('modelo')->nullable()->after('antagonista');
            $table->string('material_fornecido')->nullable()->after('modelo');
            $table->string('material_devolvido')->nullable()->after('material_fornecido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('technical_records', function (Blueprint $table) {
            $table->dropColumn(['escala', 'antagonista', 'modelo', 'material_fornecido', 'material_devolvido']);
        });
    }
};
