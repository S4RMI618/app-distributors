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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Referencia al usuario que creó la orden
            $table->unsignedBigInteger('customer_id'); // Añadido para referencia al cliente
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total_tax', 10, 2); // Impuestos
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pendiente', 'facturado'])->default('pendiente');// Estados: pending, billed
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customer_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
