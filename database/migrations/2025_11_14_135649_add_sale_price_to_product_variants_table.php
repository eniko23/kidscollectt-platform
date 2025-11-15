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
        Schema::table('product_variants', function (Blueprint $table) {
            // 'bayii_price' sütunundan sonra 'sale_price' (İndirimli Fiyat) sütununu ekliyoruz.
            // Bu da kuruş cinsinden olacak ve boş bırakılabilecek (nullable).
            $table->unsignedInteger('sale_price')->nullable()->after('bayii_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('sale_price');
        });
    }
};