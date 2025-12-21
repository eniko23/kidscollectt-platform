<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Livewire\Component;
use App\Support\VatCalculator;

class ProductDetail extends Component
{
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

    public function mount(Product $product)
    {
        $this->product = $product;
        
        // Eager load media to prevent N+1 and ensure availability
        $this->variants = ProductVariant::with('media')
            ->where('product_id', $this->product->id)
            ->get();

        // Renk listesi (color_name ve color_code'u birlikte al)
        $this->uniqueColors = $this->variants
            ->map(function ($variant) {
                return [
                    'name' => $variant->color_name,
                    'code' => $variant->color_code,
                    'name_2' => $variant->color_name_2,
                    'code_2' => $variant->color_code_2 
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
        
        $this->availableSizes = collect();

        // Başlangıçtaki ana resmi ayarla
        $this->mainImageUrl = $this->product->getFirstMedia('product-images')?->getUrl()
            ?? 'https://placehold.co/600x600/e2e8f0/94a3b8?text=Resim+Yok';
    }

    public function updatedSelectedColor($newColor)
    {
        $this->selectedSize = null;
        $this->selectedVariant = null;
        $this->quantity = 1;

        $this->availableSizes = $this->variants
            ->where('color_name', $newColor)
            ->pluck('size')
            ->unique();
        
        // Find FIRST variant of this color THAT HAS MEDIA
        $variantWithImage = $this->variants
            ->where('color_name', $newColor)
            ->filter(fn($v) => $v->hasMedia('variant-images'))
            ->first();

        if ($variantWithImage) {
            $this->mainImageUrl = $variantWithImage->getFirstMedia('variant-images')->getUrl();
        } else {
             // Fallback to product image if no variant has image
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
        $this->quantity = 1; 

        if ($this->selectedColor && $newSize) {
            $this->selectedVariant = $this->variants
                ->where('color_name', $this->selectedColor)
                ->where('size', $newSize)
                ->first();
        }
    }

    public function addToCart()
    {
        if (! $this->selectedVariant) {
            session()->flash('error', 'Lütfen renk ve beden seçin.');
            return;
        }
        if ($this->quantity > $this->selectedVariant->stock) {
            session()->flash('error', 'Seçilen adet stok miktarından fazla olamaz.');
            return;
        }

        $variant = $this->selectedVariant;
        $normalPriceKrs = $variant->price;
        $salePriceKrs = $variant->sale_price;

        if ($this->product->vat_rate > 0) {
            $normalPriceKrs = VatCalculator::calculate($normalPriceKrs, $this->product->vat_rate);
            if ($salePriceKrs) {
                $salePriceKrs = VatCalculator::calculate($salePriceKrs, $this->product->vat_rate);
            }
        }

        $hasDiscount = ($salePriceKrs && $salePriceKrs > 0 && $salePriceKrs < $normalPriceKrs);
        
        $finalPriceKrs = $hasDiscount ? $salePriceKrs : $normalPriceKrs;

        $this->dispatch('product-added-to-cart', [
            'variant_id' => $variant->id,
            'quantity' => $this->quantity,
            'product_name' => $this->product->name,
            'variant_name' => $variant->size . ' / ' . $variant->color_name,
            'price_krs' => $finalPriceKrs, 
            'image_url' => $this->mainImageUrl 
        ]);

        session()->flash('success', $this->product->name . ' başarıyla sepetinize eklendi!');
    }
    
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
