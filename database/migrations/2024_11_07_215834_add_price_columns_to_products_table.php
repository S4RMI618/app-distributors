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
        Schema::table('products', function (Blueprint $table) {
            // Renombrar base_price a base_price_1
            $table->renameColumn('base_price', 'base_price_1');

            // Crear columnas base_price2 y base_price_3
            $table->decimal('base_price_2', 10, 2)->nullable()->after('base_price_1'); 
            $table->decimal('base_price_3', 10, 2)->nullable()->after('base_price_2'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Revertir el renombrado de base_price_1 a base_price
            $table->renameColumn('base_price_1', 'base_price');
            
            // Eliminar las columnas base_price_2 y base_price_3
            $table->dropColumn(['base_price_2', 'base_price_3']);
        });
    }
};
