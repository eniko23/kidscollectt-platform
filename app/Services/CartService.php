<?php

namespace App\Services;

use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Support\VatCalculator;
use Illuminate\Support\Facades\Session;

class CartService
{
    const SESSION_KEY = 'cart';
    const COUPON_KEY = 'cart_coupon';

    /**
     * Sepete ürün ekle
     */
    // ... (add, remove, update fonksiyonlarınız aynı kalıyor) ...
    
    public function add(int $variantId, int $quantity = 1): bool
    {
        // Bu fonksiyonun değişmesine gerek yok, 
        // çünkü fiyatı değil, sadece ID'yi saklıyor.
        $variant = ProductVariant::find($variantId);
        
        if (!$variant || $variant->stock < $quantity) {
            return false;
        }

        $cart = $this->getCart();
        
        if (isset($cart[$variantId])) {
            $newQuantity = $cart[$variantId]['quantity'] + $quantity;
            if ($newQuantity > $variant->stock) {
                return false;
            }
            $cart[$variantId]['quantity'] = $newQuantity;
        } else {
            $cart[$variantId] = [
                'variant_id' => $variantId,
                'quantity' => $quantity,
            ];
        }

        Session::put(self::SESSION_KEY, $cart);
        return true;
    }
    
    // ... (remove, update fonksiyonları da aynı) ...
    public function remove(int $variantId): bool
    {
        $cart = $this->getCart();
        
        if (isset($cart[$variantId])) {
            unset($cart[$variantId]);
            Session::put(self::SESSION_KEY, $cart);
            return true;
        }

        return false;
    }

    public function update(int $variantId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->remove($variantId);
        }

        $variant = ProductVariant::find($variantId);
        
        if (!$variant || $variant->stock < $quantity) {
            return false;
        }

        $cart = $this->getCart();
        
        if (isset($cart[$variantId])) {
            $cart[$variantId]['quantity'] = $quantity;
            Session::put(self::SESSION_KEY, $cart);
            return true;
        }

        return false;
    }
    

    /**
     * Sepeti temizle
     */
    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
        Session::forget(self::COUPON_KEY);
    }

    // ... (getCart, getTotalItems aynı) ...
    public function getCart(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function getTotalItems(): int
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'quantity'));
    }

    // ===================================================================
    // === 'getItems' FONKSİYONU GÜNCELLENDİ ===
    // ===================================================================
    public function getItems(): array
    {
        $cart = $this->getCart();
        $items = [];

        foreach ($cart as $variantId => $item) {
            $quantity = is_array($item) ? ($item['quantity'] ?? 1) : 1;
            
            // 'product' ilişkisini de çekmek iyi bir pratik
            $variant = ProductVariant::with('product')->find($variantId); 
            
            if ($variant) {
                
                // --- 1. Doğru Fiyatı Hesapla ---
                // KDV Dahil Fiyatları alıyoruz
                $vatRate = $variant->product?->vat_rate ?? 0;
                $normalPrice = VatCalculator::calculate($variant->price, $vatRate);
                $salePrice = $variant->sale_price ? VatCalculator::calculate($variant->sale_price, $vatRate) : null;

                // Geçerli bir indirim var mı? 
                // (Dolu, 0'dan büyük ve normal fiyattan düşük mü?)
                $hasDiscount = ($salePrice && $salePrice > 0 && $salePrice < $normalPrice);
                
                // Sepete eklenecek nihai fiyatı (Kuruş) belirle
                $finalPrice = $hasDiscount ? $salePrice : $normalPrice;
                // --- Bitiş ---

                // --- 2. Listeye Ekle ---
                $items[] = [
                    'variant_id' => $variantId,
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'price' => $finalPrice, // <-- DÜZELTİLDİ: Nihai fiyat
                    'subtotal' => $finalPrice * $quantity, // <-- DÜZELTİLDİ: Nihai ara toplam
                    
                    // Sepet sayfasında (örn: 100 TL üzeri 80 TL) 
                    // göstermek için ekstra bilgi:
                    'original_price' => $normalPrice,
                    'has_discount' => $hasDiscount,
                ];
            }
        }
        return $items;
    }

    // ===================================================================
    // === 'getSubtotal' FONKSİYONU (DEĞİŞİKLİK GEREKMEZ) ===
    // ===================================================================
    // Bu fonksiyon 'getItems()'ı kullandığı için OTOMATİK olarak düzelecektir.
    public function getSubtotal(): int
    {
        $items = $this->getItems();
        // 'subtotal' artık 'finalPrice * quantity' olduğu için bu toplam doğru olacaktır.
        return array_sum(array_column($items, 'subtotal'));
    }

    public function has(int $variantId): bool
    {
        $cart = $this->getCart();
        return isset($cart[$variantId]);
    }

    // --- (Kupon Fonksiyonları aynı kalıyor) ---

    public function applyCoupon(Coupon $coupon): void
    {
        Session::put(self::COUPON_KEY, $coupon);
    }

    public function getAppliedCoupon(): ?Coupon
    {
        return Session::get(self::COUPON_KEY);
    }

    public function removeCoupon(): void
    {
        Session::forget(self::COUPON_KEY);
    }
}