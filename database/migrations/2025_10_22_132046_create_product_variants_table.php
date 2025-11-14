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
    Schema::create('product_variants', function (Blueprint $table) {
        $table->id();

        // --- EKSİK OLAN KRİTİK SÜTUN ---
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

        // --- FAZ 1, GÖREV 1: AKILLI VARYANT ---
        $table->string('size'); // Beden (örn: "S", "M", "2-3 Yaş")
        $table->string('color_name')->nullable(); // Renk Adı (örn: "Kırmızı")
        $table->string('color_code')->nullable(); // Renk Kodu (örn: "#FF0000")
        $table->unsignedInteger('min_quantity')->default(1); // Asgari alım adeti

        // --- FAZ 1, GÖREV 2: BAYİ FİYATI ---
        $table->unsignedInteger('price'); // Normal Fiyat (Kuruş)
        $table->unsignedInteger('bayii_price')->nullable(); // Bayi Toptan Fiyatı (Kuruş)

        // --- DİĞER ALANLAR ---
        $table->unsignedInteger('stock')->default(0);
        $table->string('sku')->unique()->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
