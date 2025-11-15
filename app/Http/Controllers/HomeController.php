<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Setting;  // <-- EKLENDİ
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Ana sayfa (vitrin) gösterir
     */
    public function index()
{
    // 1. Tüm Site Ayarlarını veritabanından çek (en verimli yöntem)
    $settings = Setting::pluck('value', 'key')->toArray();

    // 2. "Haftanın Fırsatı" ayarlarını değişkenlere ata
    $featuredProductId = $settings['featured_product_id'] ?? null;
    $salePrice = $settings['featured_product_price'] ?? null;
    $expiresAt = $settings['featured_product_expires_at'] ?? null;

    // 3. Fırsat ürününü ID'si varsa bul
    $featuredProduct = $featuredProductId ? Product::find($featuredProductId) : null;

    // 4. Ana sayfadaki "Yeni Gelenler" (Tab 1) için son 8 ürünü çek
    $latestProducts = Product::where('is_active', true)->latest()->take(8)->get();

    // 5. "Çok Satanlar" (Tab 2) için (şimdilik) rastgele ürünleri çek
    // (İleride buraya gerçek satış verisi eklenebilir)
    $bestsellerProducts = Product::where('is_active', true)->inRandomOrder()->take(8)->get();

    // 6. Soldaki Kategori Menüsü (Sidebar) için kategorileri çek
    $categories = Category::whereNull('parent_id')->orderBy('name')->get();

    // 7. Tüm bu verileri 'welcome' view'ına gönder
    return view('welcome', [
        'settings' => $settings, // Tüm ayarlar (belki kargo için lazım olur)
        'featuredProduct' => $featuredProduct,
        'salePrice' => $salePrice,
        'expiresAt' => $expiresAt,
        'latestProducts' => $latestProducts,
        'bestsellerProducts' => $bestsellerProducts,
        'categories' => $categories,
    ]);
}
}
