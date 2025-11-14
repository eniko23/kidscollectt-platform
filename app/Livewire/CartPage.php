<?php

namespace App\Livewire;

use App\Models\Coupon;
use App\Services\CartService;
use Livewire\Component;

class CartPage extends Component
{
    public array $items = [];
    public int $subtotal = 0;

    public string $couponCode = '';
    public ?Coupon $appliedCoupon = null;
    public int $discount = 0;
    public int $shippingCost = 0;
    public int $total = 0;

    // --- KALDIRILDI ---
    // public CartService $cartService; (Hata buydu)
    // --- KALDIRILDI ---
    
    // --- KALDIRILDI ---
    // public function boot(CartService $cartService)
    // {
    //     $this->cartService = $cartService;
    // }
    // --- KALDIRILDI ---

    /**
     * Component ilk yüklendiğinde
     * CartService'i buraya enjekte ediyoruz
     */
    public function mount(CartService $cartService)
    {
        // loadCart fonksiyonuna servisi parametre olarak iletiyoruz
        $this->loadCart($cartService);
    }

    /**
     * Sepeti yüklerken servisi parametre olarak alıyor
     */
    public function loadCart(CartService $cartService)
    {
        $this->items = $cartService->getItems();
        $this->subtotal = $cartService->getSubtotal();
        
        $this->appliedCoupon = $cartService->getAppliedCoupon(); 
        
        if ($this->appliedCoupon) {
            $this->couponCode = $this->appliedCoupon->code;
        }

        $this->calculateTotals();
    }

    /**
     * Bu fonksiyon servise ihtiyaç duymuyor, aynı kalabilir
     */
    public function calculateTotals()
    {
        $this->discount = 0; 

        if ($this->appliedCoupon) {
            if ($this->appliedCoupon->type == 'fixed') {
                $this->discount = $this->appliedCoupon->value;
            } elseif ($this->appliedCoupon->type == 'percent') {
                $this->discount = (int) ($this->subtotal * ($this->appliedCoupon->percent_off / 100));
            }
        }
        
        if ($this->appliedCoupon && $this->subtotal < $this->appliedCoupon->min_amount) {
             $this->removeCoupon(app(CartService::class)); // Servisi burada manuel çağırabiliriz
        }

        $this->shippingCost = $this->subtotal >= 100000 ? 0 : 9900;
        $this->total = $this->subtotal - $this->discount + $this->shippingCost;

        if ($this->total < 0) {
            $this->total = 0;
        }
    }

    /**
     * CartService'i buraya enjekte ediyoruz
     */
    public function applyCoupon(CartService $cartService)
    {
        $coupon = Coupon::where('code', $this->couponCode)
                        ->first();

        if (!$coupon) {
            $cartService->removeCoupon(); 
            $this->appliedCoupon = null;
            $this->calculateTotals();
            session()->flash('error_coupon', 'Geçersiz kupon kodu.');
            return;
        }
        
        if ($coupon->min_amount && $this->subtotal < $coupon->min_amount) {
            $cartService->removeCoupon();
            $this->appliedCoupon = null;
            $this->calculateTotals();
            $minAmountTL = number_format($coupon->min_amount / 100, 2, ',', '.');
            session()->flash('error_coupon', "Bu kupon için minimum {$minAmountTL} TL harcama gereklidir.");
            return;
        }

        $cartService->applyCoupon($coupon); 
        $this->appliedCoupon = $coupon;
        $this->calculateTotals();
        session()->flash('success_coupon', 'Kupon başarıyla uygulandı!');
    }

    /**
     * CartService'i buraya enjekte ediyoruz
     */
    public function removeCoupon(CartService $cartService)
    {
        $cartService->removeCoupon(); 
        $this->appliedCoupon = null;
        $this->couponCode = '';
        $this->calculateTotals();
        session()->flash('info_coupon', 'Kupon kaldırıldı.');
    }

    public function goToCheckout()
    {
        return $this->redirect(route('checkout.index'));
    }

    /**
     * CartService'i buraya enjekte ediyoruz
     * updateQuantity(..., $variantId, $quantity) blade dosyasından gelen parametreler
     */
    public function updateQuantity(CartService $cartService, $variantId, $quantity)
    {
        if ($quantity <= 0) {
            $cartService->remove($variantId);
        } else {
            $cartService->update($variantId, $quantity);
        }
        
        // loadCart'a enjekte ettiğimiz servisi iletiyoruz
        $this->loadCart($cartService);
        $this->dispatch('cart-updated');
    }

    /**
     * CartService'i buraya enjekte ediyoruz
     */
    public function removeItem(CartService $cartService, $variantId)
    {
        $cartService->remove($variantId);
        $this->loadCart($cartService);
        $this->dispatch('cart-updated');
    }

    /**
     * CartService'i buraya enjekte ediyoruz
     */
    public function incrementQuantity(CartService $cartService, $variantId)
    {
        $cart = $cartService->getCart();
        if (isset($cart[$variantId])) {
            $currentQuantity = $cart[$variantId]['quantity'];
            
            // updateQuantity'ye servisi ve diğer parametreleri iletiyoruz
            $this->updateQuantity($cartService, $variantId, $currentQuantity + 1);
        }
    }

    /**
     * CartService'i buraya enjekte ediyoruz
     */
    public function decrementQuantity(CartService $cartService, $variantId)
    {
        $cart = $cartService->getCart();
        if (isset($cart[$variantId])) {
            $currentQuantity = $cart[$variantId]['quantity'];
            
            // updateQuantity'ye servisi ve diğer parametreleri iletiyoruz
            $this->updateQuantity($cartService, $variantId, $currentQuantity - 1);
        }
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}