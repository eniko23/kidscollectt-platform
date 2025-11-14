<?php

namespace App\Services;

use App\Models\ProductVariant;
use App\Models\Coupon; // --- YENİ EKLENDİ ---
use Illuminate\Support\Facades\Session;

class CartService
{
    const SESSION_KEY = 'cart';
    const COUPON_KEY = 'cart_coupon'; // --- YENİ EKLENDİ: Kupon için session anahtarı ---

    /**
     * Sepete ürün ekle
     */
    // ... (add, remove, update fonksiyonlarınız aynı kalıyor) ...
    // ...
    public function add(int $variantId, int $quantity = 1): bool
    {
        // Bu fonksiyon değişmedi
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
        Session::forget(self::COUPON_KEY); // --- YENİ EKLENDİ: Kuponu da temizle ---
    }

    // ... (getCart, getTotalItems, getItems, getSubtotal, has fonksiyonları aynı) ...
    // ...
    public function getCart(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function getTotalItems(): int
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'quantity'));
    }

    public function getItems(): array
    {
        $cart = $this->getCart();
        $items = [];

        foreach ($cart as $variantId => $item) {
            $quantity = is_array($item) ? ($item['quantity'] ?? 1) : 1;
            $variant = ProductVariant::with('product')->find($variantId);
            
            if ($variant) {
                $items[] = [
                    'variant_id' => $variantId,
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'price' => $variant->price,
                    'subtotal' => $variant->price * $quantity,
                ];
            }
        }
        return $items;
    }

    public function getSubtotal(): int
    {
        $items = $this->getItems();
        return array_sum(array_column($items, 'subtotal'));
    }

    public function has(int $variantId): bool
    {
        $cart = $this->getCart();
        return isset($cart[$variantId]);
    }

    // --- YENİ EKLENEN KUPO FONKSİYONLARI ---

    /**
     * Kuponu session'a kaydet
     */
    public function applyCoupon(Coupon $coupon): void
    {
        Session::put(self::COUPON_KEY, $coupon);
    }

    /**
     * Kayıtlı kuponu session'dan getir
     */
    public function getAppliedCoupon(): ?Coupon
    {
        return Session::get(self::COUPON_KEY);
    }

    /**
     * Kuponu session'dan kaldır
     */
    public function removeCoupon(): void
    {
        Session::forget(self::COUPON_KEY);
    }
}