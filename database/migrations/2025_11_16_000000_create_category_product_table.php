<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // Migrate existing data
        $products = DB::table('products')->whereNotNull('category_id')->get();
        foreach ($products as $product) {
            DB::table('category_product')->insert([
                'product_id' => $product->id,
                'category_id' => $product->category_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
        });

        // Restore data (taking the first category found)
        $relations = DB::table('category_product')->get();
        foreach ($relations as $relation) {
            // We only set it if it's currently null (avoid overwriting if multiple categories existed in future scenarios)
            // But here we just want to revert what we did.
            // Since we are reverting to 1-to-Many, we can only pick one.
            $exists = DB::table('products')->where('id', $relation->product_id)->whereNotNull('category_id')->exists();
            if (!$exists) {
                DB::table('products')->where('id', $relation->product_id)->update(['category_id' => $relation->category_id]);
            }
        }

        Schema::dropIfExists('category_product');
    }
};
