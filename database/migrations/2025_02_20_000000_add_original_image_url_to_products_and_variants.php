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
            $table->text('original_image_url')->nullable()->after('is_active');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->text('original_image_url')->nullable()->after('barcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('original_image_url');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('original_image_url');
        });
    }
};
