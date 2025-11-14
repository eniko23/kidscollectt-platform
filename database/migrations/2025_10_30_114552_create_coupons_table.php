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
    Schema::create('coupons', function (Blueprint $table) {
        $table->id();

        // KUPONUN TEMEL BİLGİLERİ
        $table->string('code')->unique(); // Kupon kodu (örn: YAZ10), benzersiz (unique) olmalı
        $table->text('description')->nullable(); // Adminin göreceği açıklama (örn: "Yaz Kampanyası")

        // İNDİRİM TİPİ VE DEĞERİ
        // 'type' sütunu, indirimin "sabit tutar" mı yoksa "yüzde" mi olduğunu belirler.
        $table->string('type'); // 'fixed' (sabit) veya 'percentage' (yüzde)
        $table->unsignedInteger('value'); // İndirim değeri. 
                                          // 'type' = 'fixed' ise (5000 = 50 TL)
                                          // 'type' = 'percentage' ise (10 = %10)

        // KULLANIM KOŞULLARI
        $table->unsignedInteger('min_amount')->nullable(); // Minimum sepet tutarı (Kuruş cinsinden)
        $table->unsignedInteger('usage_limit')->nullable(); // Bu kupon toplam kaç kez kullanılabilir (Boşsa sınırsız)
        $table->timestamp('expires_at')->nullable(); // Kuponun son kullanma tarihi (Boşsa süresiz)

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
