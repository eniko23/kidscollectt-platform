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
            // 'price' kolonundan sonra, boş bırakılabilir ('nullable')
            // bir integer kolonu ekler.
            $table->integer('sale_price')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // Bir sorun olursa, bu metot kolonu geri siler.
            $table->dropColumn('sale_price');
        });
    }
};