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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // Breeze'in 'name' sütununu 2'ye ayırdık:
            $table->string('first_name')->default(''); // <-- EKLENDİ
            $table->string('last_name')->default('');  // <-- EKLENDİ

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            // --- BİREYSEL & BAYİ SİSTEMİ ALANLARI ---
            
            // Bireysel Form Alanları
            $table->string('phone')->nullable(); // Telefon (Bireysel için zorunlu değil)

            // Bayi Form Alanları (Bireysel ise 'null' (boş) olacak)
            $table->string('company_name')->nullable(); // Firma Adı
            $table->string('tax_office')->nullable();   // Vergi Dairesi
            $table->string('tax_id')->nullable();       // Vergi No

            // Bülten Aboneliği (Her ikisinde de var)
            $table->boolean('subscribed_to_newsletter')->default(false);

            // Sizin "Müşteri Grubu" ve "Admin Onayı" talepleriniz:
            $table->string('user_type')->default('bireysel'); // 'bireysel' veya 'bayi'
            $table->boolean('is_approved')->default(false); // Admin onayı (Bayi için kullanılacak)
            
            // ----------------------------------------------

            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Breeze'in şifre sıfırlama vb. için kullandığı ek tablolar
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
