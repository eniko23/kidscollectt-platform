<?php

// Gerekli sınıfları ve Volt fonksiyonlarını import ediyoruz
use App\Models\Category;
use App\Models\ProductVariant;
use function Livewire\Volt\{layout, title, state, mount, with};

// 1. Ana 'storefront' layout'unu kullan
layout('components.layouts.storefront'); 

// 2. STATE (Değişkenler):
// URL'den gelen kategoriyi tut
state('category'); 
// Filtrelemek için seçtiğimiz değerleri tutan BOŞ diziler
state(['selectedSizes' => []]);
state(['selectedColors' => []]);
// Filtre menüsünde göstereceğimiz, mevcut olan renk/bedenler
state(['availableSizes' => []]);
state(['availableColors' => []]);

// 3. MOUNT (Sayfa İlk Yüklendiğinde):
// URL'den kategoriyi al ve filtreleri doldur
mount(function (Category $category) {
    $this->category = $category;

    // Bu kategoriye ait TÜM ürünlerin ID'lerini al
    $productIds = $this->category->products()->pluck('id');

    // O ürünlere ait TÜM varyantları çek
    $variants = ProductVariant::whereIn('product_id', $productIds)
                               ->where('stock', '>', 0)
                               ->get();

    // O varyantlardan EŞSİZ beden ve renk listeleri oluştur
    // ->filter() ->unique() ->sort() zinciri, boş ve tekrar edenleri temizler
    $this->availableSizes = $variants->pluck('size')->filter()->unique()->sort();
    $this->availableColors = $variants->pluck('color_name')->filter()->unique()->sort();

    // Sayfa başlığını ayarla
    title($this->category->name);
});

// 4. WITH (Veri Çekme):
// Bu fonksiyon, sayfa her yenilendiğinde (veya bir filtreye tıklandığında) çalışır
with(fn () => [
    
    'products' => $this->category->products()
        ->where('is_active', true)
        
        // 1. FİLTRE: Eğer $selectedSizes dizisi doluysa...
        ->when($this->selectedSizes, function ($query) {
            // Ürünlerin varyantları içinde bu beden(ler)den var mı diye bak
            $query->whereHas('variants', fn($q) => $q->whereIn('size', $this->selectedSizes));
        })
        
        // 2. FİLTRE: Eğer $selectedColors dizisi doluysa...
        ->when($this->selectedColors, function ($query) {
            // Ürünlerin varyantları içinde bu renk(ler)den var mı diye bak
            $query->whereHas('variants', fn($q) => $q->whereIn('color_name', $this->selectedColors));
        })
        
        ->latest()
        ->paginate(12)
]);

?>

{{-- 
    HTML KISMI 
    (Artık burası "Hesabım" sayfası gibi 2 sütunlu)
--}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    {{-- Başlık Bölümü --}}
    <h1 class="text-4xl font-extrabold text-gray-900 mb-2">
         {{ $category->name }}
    </h1>
    <p class="text-lg text-gray-600 mb-8">
        {{ $category->description ?? 'Bu kategorideki en yeni ve en güzel ürünlerimiz.' }}
    </p>

    {{-- Ana Gövde (Sol Filtre + Sağ Ürünler) --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <aside class="lg:col-span-1">
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 sticky top-28">
                
                {{-- Beden Filtresi --}}
                @if($availableSizes->count() > 0)
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">
                        Beden
                    </h3>
                    <div class="space-y-2">
                        @foreach($availableSizes as $size)
                            <label class="flex items-center text-gray-700 hover:text-pink-600 cursor-pointer">
                                {{-- 
                                    ❗ SİHİR BURADA ❗
                                    wire:model.live="selectedSizes"
                                    Buna tıkladığın an, sayfa yenilenmeden $selectedSizes dizisi güncellenir
                                    ve 'with()' fonksiyonu yeniden çalışır.
                                --}}
                                <input 
                                    type="checkbox" 
                                    wire:model.live="selectedSizes" 
                                    value="{{ $size }}"
                                    class="h-4 w-4 rounded border-gray-300 text-pink-600 focus:ring-pink-500"
                                >
                                <span class="ml-3 text-sm">{{ $size }}</span>
                            </label>
                        @endforeach
                    </div>
                @endif
                
                {{-- Renk Filtresi --}}
                @if($availableColors->count() > 0)
                    <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4 pb-3 border-b border-gray-200">
                        Renk
                    </h3>
                    <div class="space-y-2">
                        @foreach($availableColors as $color)
                            <label class="flex items-center text-gray-700 hover:text-pink-600 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    wire:model.live="selectedColors" 
                                    value="{{ $color }}"
                                    class="h-4 w-4 rounded border-gray-300 text-pink-600 focus:ring-pink-500"
                                >
                                <span class="ml-3 text-sm">{{ $color }}</span>
                            </label>
                        @endforeach
                    </div>
                @endif
                
            </div>
        </aside>

        <main class="lg:col-span-3">
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    
                    {{-- 
                        Filtrelenmiş ürünleri listeliyoruz.
                        Oluşturduğumuz <x-product-card> component'ini kullanıyoruz.
                        (Etiketleri göstermiyoruz)
                    --}}
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                    
                </div>

                {{-- Sayfalama (Pagination) linkleri --}}
                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            @else
                <div class="col-span-full text-center py-12 bg-white rounded-xl shadow border">
                    <h2 class="text-xl text-gray-600">Aradığınız kriterlere uygun ürün bulunamadı.</h2>
                    <p class="text-sm text-gray-500 mt-2">Lütfen filtre seçiminizi temizleyip tekrar deneyin.</p>
                </div>
            @endif
        </main>

    </div>
</div>
