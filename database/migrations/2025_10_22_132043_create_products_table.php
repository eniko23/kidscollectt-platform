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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->longText('description')->nullable();
        $table->boolean('is_active')->default(true);
        $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');

        // --- FAZ 1, GÖREV 3 & 4: YENİ ÜRÜN AYARLARI ---
        $table->unsignedTinyInteger('vat_rate')->default(10); // KDV Oranı (%10 veya %20)
        $table->boolean('should_track_stock')->default(true); // Stok Takibi Yapılsın mı?
        $table->text('meta_keywords')->nullable(); // SEO Meta Anahtar Kelimeler
        $table->text('meta_description')->nullable(); // SEO Meta Açıklama
        // ----------------------------------------------

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
