<?php

namespace App\Livewire;

use App\Models\Coupon;
use App\Services\CartService;
use Livewire\Component;
use Livewire\Attributes\Layout; // Şablonu belirtmek için

#[Layout('components.layouts.storefront')] // Ana şablonu kullan
class CartPage extends Component
{
    public array $items = [];
    public int $subtotal = 0;

    public string $couponCode = '';
    public ?Coupon $appliedCoupon = null;
    
    // Hesaplanan Değerler
    public int $discount = 0;
    public int $vat = 0; // KDV
    public int $shippingCost = 0;
    public int $total = 0;

    /**
     * Component ilk yüklendiğinde çalışır
     * CartService otomatik olarak enjekte edilir (Laravel Magic!)
     */
    public function mount(CartService $cartService)
    {
        $this->loadCart($cartService);
    }

    /**
     * Sepet verilerini ve hesaplamaları yükler
     */
    public function loadCart(CartService $cartService)
    {
        $this->items = $cartService->getItems();
        $this->subtotal = $cartService->getSubtotal(); // Bu fonksiyon getItems() içindeki 'subtotal'ları toplar
        
        $this->appliedCoupon = $cartService->getAppliedCoupon(); 
        
        if ($this->appliedCoupon) {
            $this->couponCode = $this->appliedCoupon->code;
        }

        $this->calculateTotals($cartService); // Hesaplama fonksiyonuna servisi gönder
    }

    /**
     * Tüm toplamları hesaplar (İndirim, KDV, Kargo, Genel Toplam)
     */
    public function calculateTotals(CartService $cartService)
    {
        // 1. İndirim Hesabı
        $this->discount = 0; 
        if ($this->appliedCoupon) {
            if ($this->appliedCoupon->type == 'fixed') {
                $this->discount = $this->appliedCoupon->value;
            } elseif ($this->appliedCoupon->type == 'percentage') { // 'percent' değil 'percentage' (Veritabanına göre)
                $this->discount = (int) ($this->subtotal * ($this->appliedCoupon->value / 100)); // 'percent_off' değil 'value'
            }
            
            // İndirim, ara toplamdan büyük olamaz
            if ($this->discount > $this->subtotal) {
                $this->discount = $this->subtotal;
            }
        }
        
        // Eğer indirim sonrası tutar, kuponun minimum tutarının altına düşerse kuponu kaldır
        // (Not: Genelde minimum tutar, indirimden ÖNCEKİ tutara bakılarak kontrol edilir. 
        // Bu yüzden bu kontrolü applyCoupon'da yapmak daha mantıklı, burada sadece tekrar kontrol ediyoruz)
        if ($this->appliedCoupon && $this->appliedCoupon->min_amount && $this->subtotal < $this->appliedCoupon->min_amount) {
             $this->removeCoupon($cartService); 
             return; // removeCoupon zaten calculateTotals'ı tekrar çağıracak
        }

        // 2. İndirimli Ara Toplam (KDV ve Kargo buna göre hesaplanmaz, ama genel toplama etki eder)
        $discountedSubtotal = $this->subtotal - $this->discount;

        // 3. KDV Hesabı (%10) - İndirimden sonraki tutar üzerinden mi yoksa ham tutar üzerinden mi?
        // Genelde Türkiye'de KDV, indirim düşüldükten sonraki tutar üzerinden hesaplanır.
        $this->vat = (int) ($discountedSubtotal * 0.10);

        // 4. Kargo Hesabı
        // Kargo kuralı: İndirimli Ara Toplam 1000 TL ve üzeriyse ücretsiz
        $this->shippingCost = $discountedSubtotal >= 100000 ? 0 : 9900;

        // 5. Genel Toplam
        // (Ara Toplam - İndirim) + KDV + Kargo 
        // *NOT: Senin 'subtotal' dediğin rakam KDV DAHİL fiyatlar mı?*
        // E-ticarette genelde ürün fiyatları KDV Dahil girilir. 
        // Eğer öyleyse, KDV'yi eklememeli, sadece "içindeki KDV'yi göstermeliyiz".
        // Aşağıdaki formül, fiyatların KDV HARİÇ girildiği varsayımına göredir.
        // Eğer fiyatlar KDV Dahil ise: $this->total = $discountedSubtotal + $this->shippingCost;
        
        // Bizim sistemde ürünleri KDV Dahil (Piyasa standardı) gibi düşündük.
        // O yüzden KDV'yi toplama EKLEMİYORUZ, sadece bilgi olarak hesaplıyoruz.
        $this->total = $discountedSubtotal + $this->shippingCost;

        if ($this->total < 0) {
            $this->total = 0;
        }
    }

    /**
     * Kupon Uygula
     */
    public function applyCoupon(CartService $cartService)
    {
        $coupon = Coupon::where('code', $this->couponCode)->first();

        if (!$coupon) {
            // Geçersizse mevcut kuponu da kaldır (temizle)
            // Amaç sadece hata mesajı vermekse kaldırmayabiliriz de.
            // $this->removeCoupon($cartService); 
            
            session()->flash('error_coupon', 'Geçersiz kupon kodu.');
            return;
        }
        
        // Tarih kontrolü (Opsiyonel ama iyi olur)
        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
             session()->flash('error_coupon', 'Bu kuponun süresi dolmuş.');
             return;
        }
        
        if ($coupon->min_amount && $this->subtotal < $coupon->min_amount) {
            $minAmountTL = number_format($coupon->min_amount / 100, 2, ',', '.');
            session()->flash('error_coupon', "Bu kupon için minimum {$minAmountTL} TL harcama gereklidir.");
            return;
        }

        $cartService->applyCoupon($coupon); 
        $this->loadCart($cartService); // Verileri yenile
        session()->flash('success_coupon', 'Kupon başarıyla uygulandı!');
    }

    /**
     * Kupon Kaldır
     */
    public function removeCoupon(CartService $cartService)
    {
        $cartService->removeCoupon(); 
        $this->loadCart($cartService); // Verileri yenile ve toplamları tekrar hesapla
        session()->flash('info_coupon', 'Kupon kaldırıldı.');
    }

    public function goToCheckout()
    {
        return $this->redirect(route('checkout.index')); // Rota adının doğru olduğundan emin ol
    }

    /**
     * Adet Güncelle
     */
    public function updateQuantity(CartService $cartService, $variantId, $quantity)
    {
        if ($quantity <= 0) {
            $cartService->remove($variantId);
        } else {
            $cartService->update($variantId, $quantity);
        }
        
        $this->loadCart($cartService);
        $this->dispatch('cart-updated'); // Header'daki sepet ikonunu güncelle
    }

    /**
     * Ürün Sil
     */
    public function removeItem(CartService $cartService, $variantId)
    {
        $cartService->remove($variantId);
        $this->loadCart($cartService);
        $this->dispatch('cart-updated');
    }

    /**
     * Adet Artır (+)
     */
    public function incrementQuantity(CartService $cartService, $variantId)
    {
        $items = $cartService->getCart(); // Ham session verisi
        if (isset($items[$variantId])) {
            $currentQuantity = $items[$variantId]['quantity'];
            $this->updateQuantity($cartService, $variantId, $currentQuantity + 1);
        }
    }

    /**
     * Adet Azalt (-)
     */
    public function decrementQuantity(CartService $cartService, $variantId)
    {
        $items = $cartService->getCart();
        if (isset($items[$variantId])) {
            $currentQuantity = $items[$variantId]['quantity'];
            $this->updateQuantity($cartService, $variantId, $currentQuantity - 1);
        }
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}