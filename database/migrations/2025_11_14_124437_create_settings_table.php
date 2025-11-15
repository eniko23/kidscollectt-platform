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
    Schema::create('settings', function (Blueprint $table) {
        $table->id();

        // Ayarlar "anahtar" -> "değer" olarak saklanacak
        $table->string('key')->unique(); // Örn: 'featured_product_id'
        $table->text('value')->nullable(); // Örn: '1' (Ürün ID'si)

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
