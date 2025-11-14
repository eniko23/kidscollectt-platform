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
    // 'order_status_histories' yerine 'order_status_logs' (loglar) daha yaygın bir isimdir, bunu kullanalım.
    Schema::create('order_status_logs', function (Blueprint $table) {
        $table->id();

        // Hangi "Siparişe" (Order) ait olduğu
        $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

        // KAYIT DETAYLARI
        $table->string('status'); // Durum (örn: "pending", "processing", "shipped")
        $table->text('notes')->nullable(); // Açıklama (örn: "Sipariş onaylandı", "Kargo takip no: 12345")

        $table->timestamps(); // "TARİH" sütunu için bunu kullanacağız (created_at)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};
