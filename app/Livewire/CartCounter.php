<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;
use Livewire\Attributes\On;

class CartCounter extends Component
{
    public int $count = 0;

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
    #[On('cart-updated')]
    public function updateCount()
    {
        $cartService = app(CartService::class);
        $this->count = $cartService->getTotalItems();
    }

    /**
     * 'product-added-to-cart' sinyali yakalandığında çalışır.
     * $payload, ProductDetail'den gönderdiğimiz veriyi içerir (variant_id, quantity vb.)
     */
    #[On('product-added-to-cart')]
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
        $cartService->add($payload['variant_id'], $payload['quantity']);

        // 3. Adım: Sepet eklendikten sonra, ekrandaki sayacı güncelle
        $this->updateCount();
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}
