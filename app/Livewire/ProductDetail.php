<?php

namespace App\Livewire;

// 'use Cart;' satırı TAMAMEN KALDIRILDI.
// Gerekli sınıflar:
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Livewire\Component;
use App\Support\VatCalculator;

class ProductDetail extends Component
{
    // --- (Diğer tüm özellikleriniz $product, $variants vb. aynı kalacak) ---
    public Product $product;
    public Collection $variants;
    public Collection $uniqueColors;
    public Collection $uniqueSizes;
    public ?string $selectedColor = null;
    public ?string $selectedSize = null;
    public int $quantity = 1;
    public Collection $availableSizes;
    public ?ProductVariant $selectedVariant = null;
    public ?string $mainImageUrl = null;

    // --- (mount, updatedSelectedColor, updatedSelectedSize fonksiyonları aynı kalacak) ---
    
    public function mount(Product $product)
    {
        $this->product = $product;
        // Tüm varyantları çek (Stok 0 olsa bile, ki "Stokta Yok" yazabilsin)
        $this->variants = ProductVariant::where('product_id', $this->product->id)
            ->get();

        // Renk listesi (color_name ve color_code'u birlikte al)
        $this->uniqueColors = $this->variants
            ->map(function ($variant) {
                return [
                    'name' => $variant->color_name,
                    'code' => $variant->color_code,
                    'name_2' => $variant->color_name_2, // Yeni Alan
                    'code_2' => $variant->color_code_2  // Yeni Alan
                ];
            })
            ->filter(fn ($color) => !empty($color['name']))
            ->unique('name')
            ->values();

        // Beden listesi
        $this->uniqueSizes = $this->variants
            ->pluck('size')
            ->unique()
            ->values();
        
        $this->availableSizes = collect(); // Başlangıçta boş

        // Başlangıçtaki ana resmi ayarla
        $this->mainImageUrl = $this->product->getFirstMedia('product-images')?->getUrl()
            ?? 'https://placehold.co/600x600/e2e8f0/94a3b8?text=Resim+Yok';
    }

    public function updatedSelectedColor($newColor)
    {
        // ... (Bu fonksiyonun içeriği aynı kalacak) ...
        $this->selectedSize = null;
        $this->selectedVariant = null;
        $this->quantity = 1;

        $this->availableSizes = $this->variants
            ->where('color_name', $newColor)
            ->pluck('size')
            ->unique();
        
        $firstVariantOfColor = $this->variants
            ->where('color_name', $newColor)
            ->first();

        if ($firstVariantOfColor && $firstVariantOfColor->hasMedia('variant-images')) {
            $this->mainImageUrl = $firstVariantOfColor->getFirstMedia('variant-images')->getUrl();
        } else {
            $this->mainImageUrl = $this->product->getFirstMedia('product-images')?->getUrl()
                ?? 'https://placehold.co/600x600/e2e8f0/94a3b8?text=Resim+Yok';
        }
        
        if ($this->availableSizes->count() == 1) {
            $this->selectedSize = $this->availableSizes->first();
            $this->updatedSelectedSize($this->selectedSize);
        }
    }

    public function updatedSelectedSize($newSize)
    {
        // ... (Bu fonksiyonun içeriği aynı kalacak) ...
        $this->quantity = 1; 

        if ($this->selectedColor && $newSize) {
            $this->selectedVariant = $this->variants
                ->where('color_name', $this->selectedColor)
                ->where('size', $newSize)
                ->first();
        }
    }

    // ===================================================================
    // === 'addToCart' FONKSİYONU GÜNCELLENDİ (Sinyal Yöntemine Geri Dönüldü) ===
    // ===================================================================
    /**
     * Sepete Ekle butonuna tıklandığında
     */
    public function addToCart()
    {
        // --- 1. Kontroller ---
        if (! $this->selectedVariant) {
            session()->flash('error', 'Lütfen renk ve beden seçin.');
            return;
        }
        if ($this->quantity > $this->selectedVariant->stock) {
            session()->flash('error', 'Seçilen adet stok miktarından fazla olamaz.');
            return;
        }

        // --- 2. Doğru Fiyatı Hesapla (KURUŞ CİNSİNDEN) ---
        $variant = $this->selectedVariant;
        $normalPriceKrs = $variant->price;
        $salePriceKrs = $variant->sale_price;

        // KDV Ekleme (Eğer vat_rate varsa)
        if ($this->product->vat_rate > 0) {
            $normalPriceKrs = VatCalculator::calculate($normalPriceKrs, $this->product->vat_rate);
            if ($salePriceKrs) {
                $salePriceKrs = VatCalculator::calculate($salePriceKrs, $this->product->vat_rate);
            }
        }

        // Geçerli bir indirim var mı?
        $hasDiscount = ($salePriceKrs && $salePriceKrs > 0 && $salePriceKrs < $normalPriceKrs);
        
        // Sepete eklenecek nihai fiyatı (Kuruş cinsinden) belirle
        $finalPriceKrs = $hasDiscount ? $salePriceKrs : $normalPriceKrs;

        // --- 3. Sinyali Gönder (Orijinal kodunuzdaki gibi) ---
        // FİYAT BİLGİSİNİ SİNYALE EKLİYORUZ
        $this->dispatch('product-added-to-cart', [
            'variant_id' => $variant->id,
            'quantity' => $this->quantity,
            'product_name' => $this->product->name,
            'variant_name' => $variant->size . ' / ' . $variant->color_name,
            
            // --- YENİ EKLENEN VERİLER ---
            'price_krs' => $finalPriceKrs, // Sepete eklenecek KURUŞ fiyatı
            'image_url' => $this->mainImageUrl // Mini sepette gösterilecek resim
        ]);

        // --- 4. Başarı Mesajı ---
        session()->flash('success', $this->product->name . ' başarıyla sepetinize eklendi!');
    }
    
    // --- (incrementQuantity ve decrementQuantity fonksiyonları aynı kalacak) ---
    public function incrementQuantity()
    {
        if ($this->selectedVariant && $this->quantity < $this->selectedVariant->stock) {
            $this->quantity++;
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > ($this->selectedVariant->min_quantity ?? 1)) {
            $this->quantity--;
        }
    }

    public function render()
    {
        return view('livewire.product-detail');
    }
}