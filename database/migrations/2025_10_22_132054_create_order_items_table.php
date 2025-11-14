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
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        
        // Ana siparişe bağlama
        $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
        
        // Satın alınan varyanta bağlama
        $table->foreignId('product_variant_id')->constrained('product_variants');
        
        $table->unsignedInteger('quantity'); // Kaç adet alındı
        $table->unsignedInteger('price'); // Satın alındığı andaki fiyatı (kuruş)
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
