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
    Schema::create('addresses', function (Blueprint $table) {
        $table->id();

        // Adresin hangi "Müşteriye" (User) ait olduğu
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        // ADRES DETAYLARI
        $table->string('label')->nullable(); // Örn: "Ev Adresi", "İş Adresi"
        $table->string('first_name'); // Adres sahibinin adı
        $table->string('last_name');  // Adres sahibinin soyadı
        $table->string('phone')->nullable(); // Adres için telefon

        // Attığın fotoğraftaki alanlar
        $table->string('address_line_1'); // Adres 1
        $table->string('address_line_2')->nullable(); // Adres 2 (Zorunlu değil)
        $table->string('district'); // İlçe / Semt
        $table->string('city');     // Şehir
        $table->string('country')->default('Turkey'); // Ülke

        $table->boolean('is_default_shipping')->default(false); // Varsayılan Teslimat Adresi mi?
        $table->boolean('is_default_billing')->default(false);  // Varsayılan Fatura Adresi mi?

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
