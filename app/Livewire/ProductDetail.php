<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Livewire\Component;

class ProductDetail extends Component
{
    // --- DIŞARIDAN GELEN VERİ ---
    public Product $product; // Kontrolcüden gelen ana ürün

    // --- VERİTABANI VERİLERİ ---
    public Collection $variants; // Bu ürüne ait TÜM stoktaki varyantlar
    public Collection $uniqueColors; // Mevcut renkler (tekrarsız)
    public Collection $uniqueSizes;  // Mevcut bedenler (tekrarsız)

    // --- MÜŞTERİNİN SEÇİMLERİ ---
    public ?string $selectedColor = null; // Seçilen renk
    public ?string $selectedSize = null;  // Seçilen beden
    public int $quantity = 1; // Seçilen adet

    // --- HESAPLANAN DEĞERLER ---
    public Collection $availableSizes; // Seçilen renge göre stokta olan bedenler
    public ?ProductVariant $selectedVariant = null; // Seçilen son varyant (renk + beden)
    public ?string $mainImageUrl = null; // O an gösterilen ana resim URL'si

    /**
     * Bileşen ilk yüklendiğinde
     */
    public function mount(Product $product)
    {
        $this->product = $product;
        // Sadece stokta olan varyantları çek
        $this->variants = ProductVariant::where('product_id', $this->product->id)
            ->where('stock', '>', 0)
            ->get();

        // Renk listesi (color_name ve color_code'u birlikte al)
        $this->uniqueColors = $this->variants
            ->map(function ($variant) {
                return [
                    'name' => $variant->color_name,
                    'code' => $variant->color_code
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

    /**
     * Renk seçildiğinde ($selectedColor değiştiğinde) burası çalışır.
     */
    public function updatedSelectedColor($newColor)
    {
        $this->selectedSize = null;
        $this->selectedVariant = null;
        $this->quantity = 1;

        // Seçilen renge göre mevcut bedenleri bul
        $this->availableSizes = $this->variants
            ->where('color_name', $newColor)
            ->pluck('size')
            ->unique();
        
        // Ana resmi, bu rengin VARYANTINA ait resimle güncelle
        $firstVariantOfColor = $this->variants
            ->where('color_name', $newColor)
            ->first();

        if ($firstVariantOfColor && $firstVariantOfColor->hasMedia('variant-images')) {
            $this->mainImageUrl = $firstVariantOfColor->getFirstMedia('variant-images')->getUrl();
        } else {
            // Renge ait özel resim yoksa, ürünün ana resmini göster
            $this->mainImageUrl = $this->product->getFirstMedia('product-images')?->getUrl()
                ?? 'https://placehold.co/600x600/e2e8f0/94a3b8?text=Resim+Yok';
        }
        
        // Sadece 1 beden varsa onu otomatik seç
        if ($this->availableSizes->count() == 1) {
            $this->selectedSize = $this->availableSizes->first();
            $this->updatedSelectedSize($this->selectedSize); // Beden seçme fonksiyonunu tetikle
        }
    }

    /**
     * Beden seçildiğinde ($selectedSize değiştiğinde) burası çalışır.
     */
    public function updatedSelectedSize($newSize)
    {
        $this->quantity = 1; // Bedeni değiştirince adedi sıfırla

        if ($this->selectedColor && $newSize) {
            // O varyantın tamamını bul (örn: "Kırmızı" ve "S" olan)
            $this->selectedVariant = $this->variants
                ->where('color_name', $this->selectedColor)
                ->where('size', $newSize)
                ->first();
        }
    }

    /**
     * Sepete Ekle butonuna tıklandığında
     */
    public function addToCart()
    {
        if (! $this->selectedVariant) return; 
        if ($this->quantity > $this->selectedVariant->stock) return; 
        
        // Sinyali gönder
        $this->dispatch('product-added-to-cart', [
            'variant_id' => $this->selectedVariant->id,
            'quantity' => $this->quantity,
            'product_name' => $this->product->name,
            'variant_name' => $this->selectedVariant->size . ' / ' . $this->selectedVariant->color_name
        ]);

        session()->flash('success', $this->product->name . ' başarıyla sepetinize eklendi!');
    }
    
    // Adet artırma/azaltma
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