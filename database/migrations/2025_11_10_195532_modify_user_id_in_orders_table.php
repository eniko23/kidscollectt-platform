<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB; // <-- BU SATIRI EKLEDİĞİNİZDEN EMİN OLUN
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // doctrine/dbal paketine ihtiyaç DUYMAYAN yöntem
        // 'user_id' sütununu 'boş bırakılabilir' (NULL) olarak değiştiriyoruz
        DB::statement('ALTER TABLE orders MODIFY user_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri alma işlemi (eski haline, 'boş bırakılamaz' haline)
        DB::statement('ALTER TABLE orders MODIFY user_id BIGINT UNSIGNED NOT NULL');
    }
};