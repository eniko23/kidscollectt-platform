<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// --- BİZİM KONTROLCÜLERİMİZ ---
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Models\Category;

use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\PaymentController;


// ===================================================================
// VİTRİN (ÖN YÜZ) ROTALARI
// ===================================================================
// ... (Ana Sayfa, Kategori, Ürünler, Sepet, Ödeme rotalarınız burada) ...
Route::get('/', [HomeController::class, 'index'])->name('home');
//Route::get('/kategori/{category:slug}', [ProductController::class, 'showCategory'])->name('category.show');
Volt::route('/kategori/{category:slug}', 'products.category-page')
    ->name('category.show');
Route::get('/urunler', [ProductController::class, 'index'])->name('products.index');
Route::get('/urun/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/sepet', function () {
    return view('cart.index');
})->name('cart.index');
Route::get('/odeme', function () {
    return view('checkout.index');
})->name('checkout.index');


// Ödeme Sayfası (Kullanıcı "Ödeme Yap" deyince buraya gelir)
Route::get('/odeme-yap/{order}', [PaymentController::class, 'payment'])->name('payment.show');

// PayTR Callback (PayTR sunucusu buraya istek atar - POST olmalı!)
// Bu rotayı CSRF korumasından hariç tutmamız gerekecek (Adım 3'te anlatacağım)
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

// Sadece Yöneticiler Erişebilmeli!
Route::post('/admin/orders/{order}/refund', [PaymentController::class, 'refund'])
    ->name('admin.orders.refund')
    ->middleware(['auth']); // Veya 'admin' middleware'i

Route::get('/odeme-basarili', function () {
    // Session'dan ID alıyoruz
    $orderId = session('order_id_for_payment') ?? session('order_created');

    // DÜZELTME BURADA:
    // 'thank-you' yerine 'payment.thank-you' yazıyoruz.
    // Bu, "payment klasörünün içindeki thank-you dosyası" demektir.
    return view('payment.thank-you', ['order_id' => $orderId]); 
    
})->name('payment.success');

// Başarılı / Başarısız Dönüş Sayfaları
Route::get('/odeme/basarili', function() {
    return view('payment.success'); // Basit bir "Teşekkürler" sayfası
})->name('payment.success');

Route::get('/odeme/basarisiz', function() {
    return view('payment.fail'); // "Ödeme Alınamadı" sayfası
})->name('payment.fail');


// --- YENİ EKLENECEK ROTA (SİPARİŞ BAŞARILI) ---
Route::get('/siparis-tamamlandi', function () {
    // Eğer sipariş yeni oluşturulmadıysa (URL'yi direkt yazdılarsa) ana sayfaya at
    if (! session('order_created')) {
        return redirect()->route('home');
    }
    
    // 'order_created' flash mesajı (sipariş ID'sini içerir)
    // view'e (görünüme) gönderilir.
    return view('payment.thank-you', [
        'order_id' => session('order_created')
    ]);
})->name('payment.thank-you');

// (Hazır el atmışken, Kredi Kartı ödemesi için olanı da ekleyelim)
Route::get('/odeme-islemi', function () {
     if (! session('order_id_for_payment')) {
        return redirect()->route('home');
    }
    // Burası Iyzico/PayTR gibi ödeme sağlayıcısına yönlendirme yapacağın yer
    return 'Ödeme Sağlayıcıya Yönlendirme Ekranı...';
})->name('payment.process');
// --- YENİ ROTALAR BİTTİ ---


// --- YENİ EKLENECEK MİSAFİR (GUEST) ROTALARI ---
// Bu blok, sadece GİRİŞ YAPMAMIŞ kullanıcıların
// "Giriş Yap" ve "Kayıt Ol" sayfalarını görmesini sağlar.
Route::middleware('guest')->group(function () {
    
    // Giriş Yap (Login) Sayfası
    Route::get('login', function () {
        // resources/views/livewire/auth/login.blade.php dosyasını gösterecek
        return view('livewire.auth.login');
    })->name('login');

    // Kayıt Ol (Register) Sayfası
    Route::get('register', function () {
        // resources/views/livewire/auth/register.blade.php dosyasını gösterecek
        return view('livewire.auth.register');
    })->name('register');
});


// ===================================================================
// OTURUM (AUTH) VE KULLANICI PANELİ ROTALARI
// ===================================================================
// Bunlar Breeze/Fortify tarafından kuruldu, dokunmuyoruz.

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('hesabim/siparislerim', 'account.orders')->name('account.orders');

    Volt::route('hesabim/siparislerim', 'account.orders')->name('account.orders');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});


Route::get('/link-storage', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');
    
    if (file_exists($link)) {
        return 'Link zaten var!';
    }
    
    symlink($target, $link);
    return 'Storage linki başarıyla oluşturuldu!';
});