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
    Schema::create('wishlists', function (Blueprint $table) {
        $table->id();

        // Hangi "Müşteriye" (User) ait olduğu
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        // Hangi "Ürüne" (Product) ait olduğu
        // (Genellikle bir varyant değil, ana ürün favorilere eklenir)
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

        // Bir kullanıcının aynı ürünü 2 kez favoriye eklemesini engelle
        $table->unique(['user_id', 'product_id']);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
