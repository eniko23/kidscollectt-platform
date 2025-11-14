<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;

class CartCounter extends Component
{
    public int $count = 0;

    /**
     * Bileşenin hangi sinyalleri dinleyeceğini belirler.
     * Livewire 3'te modern yöntem #[On] olsa da, mevcut kodunuzdaki
     * getListeners() yapısını koruyarak devam ediyorum.
     */
    protected function getListeners()
    {
        return [
            // Bu zaten vardı, sepetin başka bir yerden (örn: sepet sayfası)
            // güncellenmesi durumunda sayacı yeniler.
            'cart-updated' => 'updateCount',

            // --- YENİ EKLENEN SATIR ---
            // ProductDetail'den gelen sinyali yakalar ve 'handleProductAdded' fonksiyonunu çalıştırır.
            'product-added-to-cart' => 'handleProductAdded',
        ];
    }

    /**
     * Bileşen ilk yüklendiğinde
     */
    public function mount()
    {
        // Sayfa yüklendiğinde mevcut sepet sayısını göster
        $this->updateCount();
    }

    /**
     * Sepetteki toplam ürün sayısını günceller
     */
    public function updateCount()
    {
        $cartService = app(CartService::class);
        $this->count = $cartService->getTotalItems();
    }

    /**
     * --- YENİ EKLENEN FONKSİYON ---
     * 'product-added-to-cart' sinyali yakalandığında çalışır.
     * $payload, ProductDetail'den gönderdiğimiz veriyi içerir (variant_id, quantity vb.)
     */
    public function handleProductAdded($payload)
    {
        // Gelen verinin (payload) boş veya hatalı olup olmadığını kontrol et
        if (empty($payload['variant_id']) || empty($payload['quantity'])) {
            // Hata varsa (ki olmamalı), işlemi durdur
            return;
        }

        // 1. Adım: CartService'i çağır
        $cartService = app(CartService::class);

        // 2. Adım: Ürünü sepete ekle
        // (Burada servisteki fonksiyon adının 'addItem' olduğunu varsayıyorum)
        $cartService->add($payload['variant_id'], $payload['quantity']);

        // 3. Adım: Sepet eklendikten sonra, ekrandaki sayacı güncelle
        $this->updateCount();
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}