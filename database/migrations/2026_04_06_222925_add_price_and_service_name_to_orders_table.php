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
        Schema::table('orders', function (Blueprint $col) {
            $col->decimal('price', 10, 2)->nullable()->after('status');
            $col->string('service_name')->nullable()->after('price');
            $col->boolean('is_invoiced')->default(false)->after('service_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $col) {
            $col->dropColumn(['price', 'service_name', 'is_invoiced']);
        });
    }
};
