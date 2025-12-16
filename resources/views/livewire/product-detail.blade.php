{{-- Çocuk Mağazasına Uygun Renkli ve Eğlenceli Tasarım --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    {{-- Başarı/Hata Mesajları --}}
    @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md animate-bounce">
            <p class="font-bold">🎉 {{ session('success') }}</p>
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md">
            <p class="font-bold">⚠️ {{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
        
        {{-- Sol Taraf: Ürün Resimleri --}}
        <div class="space-y-4">
            {{-- Ana Resim --}}
            <div class="relative bg-gradient-to-br from-pink-50 to-purple-50 rounded-2xl overflow-hidden shadow-xl border-4 border-white">
                <div class="aspect-square w-full">
                    <img src="{{ $mainImageUrl }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                </div>
                @if($selectedVariant && $selectedVariant->stock <= 0)
                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                        <span class="bg-red-500 text-white px-6 py-3 rounded-full text-xl font-bold shadow-lg">
                            Stokta Yok
                        </span>
                    </div>
                @endif
            </div>

            {{-- Thumbnail Resimler --}}
            @if($product->getFirstMedia('product-images') || $product->getMedia('product-gallery')->count() > 0 || $variants->filter(fn($v) => $v->hasMedia('variant-images'))->count() > 0)
                <div class="grid grid-cols-4 sm:grid-cols-6 gap-3">
                    {{-- Ana Ürün Resmi --}}
                    @if($product->getFirstMedia('product-images'))
                        <button 
                            wire:click="$set('mainImageUrl', '{{ $product->getFirstMedia('product-images')->getUrl() }}')" 
                            class="relative aspect-square rounded-xl overflow-hidden border-4 transition-all duration-200 hover:scale-110
                                   {{ $mainImageUrl == $product->getFirstMedia('product-images')->getUrl() ? 'border-pink-500 ring-4 ring-pink-200 shadow-lg' : 'border-white hover:border-pink-300' }}">
                            <img src="{{ $product->getFirstMedia('product-images')->getUrl() }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover">
                        </button>
                    @endif
                    
                    {{-- Galeri Resimleri --}}
                    @foreach($product->getMedia('product-gallery') as $media)
                        <button 
                            wire:click="$set('mainImageUrl', '{{ $media->getUrl() }}')"
                            class="relative aspect-square rounded-xl overflow-hidden border-4 transition-all duration-200 hover:scale-110
                                   {{ $mainImageUrl == $media->getUrl() ? 'border-pink-500 ring-4 ring-pink-200 shadow-lg' : 'border-white hover:border-pink-300' }}">
                            <img src="{{ $media->getUrl() }}" alt="" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                    
                    {{-- Varyant Resimleri --}}
                    @foreach($variants->filter(fn($v) => $v->hasMedia('variant-images'))->unique('color_name') as $variant)
                        <button 
                            wire:click="$set('mainImageUrl', '{{ $variant->getFirstMedia('variant-images')->getUrl() }}')"
                            class="relative aspect-square rounded-xl overflow-hidden border-4 transition-all duration-200 hover:scale-110
                                   {{ $mainImageUrl == $variant->getFirstMedia('variant-images')->getUrl() ? 'border-pink-500 ring-4 ring-pink-200 shadow-lg' : 'border-white hover:border-pink-300' }}">
                            <img src="{{ $variant->getFirstMedia('variant-images')->getUrl() }}" 
                                 alt="{{ $variant->color_name }}" 
                                 class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Sağ Taraf: Ürün Bilgileri --}}
        <div class="space-y-6">
            {{-- Ürün Adı --}}
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3 bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent">
                    {{ $product->name }}
                </h1>
                
                {{-- Fiyat (GÜNCELLENDİ) --}}
<div class="flex items-baseline gap-3 mt-4">
    
    @if($selectedVariant)
        {{-- 1. VARYANT SEÇİLDİYSE --}}
        @php
            $normalPrice = $selectedVariant->price;
            $salePrice = $selectedVariant->sale_price;

            // KDV Ekleme
            if($product->vat_rate > 0) {
                $normalPrice = \App\Support\VatCalculator::calculate($normalPrice, $product->vat_rate);
                if($salePrice) {
                    $salePrice = \App\Support\VatCalculator::calculate($salePrice, $product->vat_rate);
                }
            }
            
            // Geçerli bir indirim var mı? (sale_price dolu, 0'dan büyük ve normal fiyattan düşük)
            $hasDiscount = ($salePrice && $salePrice > 0 && $salePrice < $normalPrice);
        @endphp

        @if($hasDiscount)
            {{-- İndirim Varsa: Yeni Fiyat + Üstü Çizili Eski Fiyat --}}
            <p class="text-4xl font-bold text-pink-600">
                {{ number_format($salePrice / 100, 2, ',', '.') }} TL
            </p>
            <p class="text-2xl font-normal text-gray-400 line-through ml-2">
                {{ number_format($normalPrice / 100, 2, ',', '.') }} TL
            </p>
        @else
            {{-- İndirim Yoksa: Sadece Normal Fiyat --}}
            <p class="text-4xl font-bold text-pink-600">
                {{ number_format($normalPrice / 100, 2, ',', '.') }} TL
            </p>
        @endif

    @elseif($variants->count() > 0)
        {{-- 2. VARYANT SEÇİLMEDİYSE (Sayfa ilk yüklendiğinde) --}}
        {{-- Product Model'e eklediğimiz Accessor'u (display_price) kullanalım --}}
        <p class="text-4xl font-bold text-pink-600">
            {{ $product->display_price }} TL 
            @if($product->display_old_price)
                {{-- Eğer en düşük fiyat bir indirimse, eski fiyatı da göster --}}
                <span class="text-2xl font-normal text-gray-400 line-through ml-2">
                    {{ $product->display_old_price }} TL
                </span>
            @endif
        </p>
         <span class="text-lg text-gray-500 font-normal ml-2">'den başlayan fiyatlar</span>
        
    @else
        {{-- 3. HİÇ VARYANT YOKSA --}}
        <p class="text-3xl font-bold text-red-500">
            Stokta Yok
        </p>
    @endif

</div>
            </div>

            {{-- Açıklama --}}
            @if($product->description)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {!! $product->description !!}
                    </div>
                </div>
            @endif

            {{-- Renk Seçimi --}}
            @if($uniqueColors->count() > 0)
                <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-pink-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="text-2xl">🎨</span>
                        <span>Renk Seçin</span>
                        @if($selectedColor)
                            <span class="text-pink-600">: {{ $selectedColor }}</span>
                        @endif
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach($uniqueColors as $color)
                            <label class="cursor-pointer group relative">
                                <input 
                                    type="radio" 
                                    name="selectedColor" 
                                    value="{{ $color['name'] }}"
                                    wire:model.live="selectedColor"
                                    class="sr-only peer">
                                <div class="relative">
                                    <div 
                                        class="w-14 h-14 rounded-full border-4 transition-all duration-200
                                               peer-checked:border-pink-500 peer-checked:ring-4 peer-checked:ring-pink-200 peer-checked:scale-110 peer-checked:shadow-lg
                                               border-gray-300 group-hover:border-pink-300 group-hover:scale-105" 
                                        style="@if(!empty($color['code_2'])) background: linear-gradient(45deg, {{ $color['code'] ?? '#ccc' }} 50%, {{ $color['code_2'] }} 50%); @else background-color: {{ $color['code'] ?? '#ccc' }}; @endif">
                                    </div>
                                    @if($selectedColor == $color['name'])
                                        <div class="absolute -top-1 -right-1 bg-pink-500 rounded-full p-1">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <p class="text-xs text-center mt-1 text-gray-600 font-medium">{{ $color['name'] }}</p>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Beden Seçimi --}}
            @if($uniqueSizes->count() > 0)
                <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-purple-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="text-2xl">📏</span>
                        <span>Beden Seçin</span>
                        @if($selectedSize)
                            <span class="text-purple-600">: {{ $selectedSize }}</span>
                        @endif
                    </h3>
                    <div class="grid grid-cols-4 sm:grid-cols-6 gap-3">
                        @foreach($uniqueSizes as $size)
                            @php
                                $isAvailable = $selectedColor ? $availableSizes->contains($size) : true;
                                $variantForSize = $selectedColor 
                                    ? $variants->where('color_name', $selectedColor)->where('size', $size)->first()
                                    : $variants->where('size', $size)->first();
                                $hasStock = $variantForSize && $variantForSize->stock > 0;
                            @endphp
                            
                            @if($isAvailable && $hasStock)
                                <label class="relative cursor-pointer">
                                    <input 
                                        type="radio" 
                                        name="selectedSize" 
                                        value="{{ $size }}"
                                        wire:model.live="selectedSize"
                                        class="sr-only peer">
                                    <div class="relative">
                                        <div 
                                            class="flex items-center justify-center h-14 rounded-xl border-4 font-bold text-lg transition-all duration-200
                                                   peer-checked:border-purple-500 peer-checked:bg-purple-100 peer-checked:text-purple-700 peer-checked:ring-4 peer-checked:ring-purple-200 peer-checked:scale-105 peer-checked:shadow-lg
                                                   border-gray-300 bg-white text-gray-700 hover:border-purple-300 hover:bg-purple-50 hover:scale-105">
                                            {{ $size }}
                                        </div>
                                        @if($selectedSize == $size)
                                            <div class="absolute -top-2 -right-2 bg-purple-500 rounded-full p-1">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </label>
                            @else
                                <div class="relative cursor-not-allowed opacity-50">
                                    <div class="flex items-center justify-center h-14 rounded-xl border-4 font-bold text-lg border-gray-200 bg-gray-100 text-gray-400">
                                        {{ $size }}
                                    </div>
                                    @if(!$hasStock && $isAvailable)
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <span class="text-xs text-red-500 font-bold">Yok</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Adet Seçimi --}}
            @if($selectedVariant)
                <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-blue-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="text-2xl">🔢</span>
                        <span>Adet</span>
                    </h3>
                    <div class="flex items-center gap-4">
                        <button 
                            wire:click="decrementQuantity" 
                            @if($quantity <= ($selectedVariant->min_quantity ?? 1)) disabled @endif
                            class="w-12 h-12 rounded-full bg-blue-500 text-white font-bold text-xl hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 hover:scale-110 shadow-lg">
                            -
                        </button>
                        <input type="text" 
                               value="{{ $quantity }}" 
                               class="w-20 h-12 text-center text-2xl font-bold border-4 border-blue-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-200" 
                               readonly>
                        <button 
                            wire:click="incrementQuantity" 
                            @if($selectedVariant && $quantity >= $selectedVariant->stock) disabled @endif 
                            class="w-12 h-12 rounded-full bg-blue-500 text-white font-bold text-xl hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 hover:scale-110 shadow-lg">
                            +
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mt-3 text-center">
                        @if($selectedVariant->stock > 0)
                            <span class="text-green-600 font-bold">✅ Stokta {{ $selectedVariant->stock }} adet mevcut</span>
                        @else
                            <span class="text-red-600 font-bold">❌ Stokta yok</span>
                        @endif
                    </p>
                </div>
            @endif

            {{-- Sepete Ekle Butonu --}}
            <button 
                type="button"
                wire:click="addToCart" 
                @if(!$selectedVariant || ($selectedVariant && $selectedVariant->stock <= 0)) disabled @endif 
                class="w-full py-5 rounded-2xl text-xl font-bold text-white transition-all duration-300 transform hover:scale-105 hover:shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed
                       {{ $selectedVariant && $selectedVariant->stock > 0
                           ? 'bg-gradient-to-r from-pink-500 via-purple-500 to-pink-500 hover:from-pink-600 hover:via-purple-600 hover:to-pink-600 shadow-xl cursor-pointer'
                           : 'bg-gray-400 cursor-not-allowed' }}">
                @if($selectedVariant && $selectedVariant->stock > 0)
                    <span class="flex items-center justify-center gap-3">
                        <span class="text-2xl">🛒</span>
                        <span>Sepete Ekle</span>
                    </span>
                @else
                    <span>Önce Renk ve Beden Seçin</span>
                @endif
            </button>

            {{-- Stok Durumu Mesajları --}}
            <div class="space-y-2">
                @if($selectedColor && $availableSizes->isEmpty())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <p class="text-red-700 font-bold">⚠️ Bu renkte stok kalmadı.</p>
                    </div>
                @elseif($selectedColor && $selectedSize && !$selectedVariant)
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <p class="text-red-700 font-bold">⚠️ Seçili beden stokta yok.</p>
                    </div>
                @endif
            </div>

            </div> 

            {{-- ========================================== --}}
            {{-- PAYTR TAKSİT TABLOSU BAŞLANGIÇ --}}
            {{-- ========================================== --}}
            
            {{-- ========================================== --}}
            {{-- PAYTR TAKSİT TABLOSU (İNDİRİM + KDV FİXLİ) --}}
            {{-- ========================================== --}}
            
            @php
                $paytrAmount = "0.00"; 
                $basePrice = 0; // KDV Hariç, İndirim Dahil Ham Fiyat
                $vatRate = $product->vat_rate ?? 0; // KDV Oranı (Örn: 10, 20)

                // --- 1. EN DOĞRU FİYATI SEÇME MANTIĞI ---
                
                if ($selectedVariant) {
                    // Varyant Seçiliyse
                    $p = $selectedVariant->price;      // Normal Fiyat
                    $s = $selectedVariant->sale_price; // İndirimli Fiyat

                    // İndirim geçerli mi? (0'dan büyük ve normalden küçük olmalı)
                    if ($s && $s > 0 && $s < $p) {
                        $basePrice = $s;
                    } else {
                        $basePrice = $p;
                    }
                } 
                elseif ($variants && $variants->count() > 0) {
                    // Varyant Seçili Değilse: En ucuz varyantı bul
                    $basePrice = $variants->map(function($v) {
                        $p = $v->price;
                        $s = $v->sale_price;
                        // Her varyant için efektif fiyatı bul
                        return ($s && $s > 0 && $s < $p) ? $s : $p;
                    })->min();
                }
                else {
                    // Varyantsız Ürün
                    $p = $product->price;
                    $s = $product->sale_price ?? 0; // Ürün modelinde sale_price olmayabilir, kontrol et
                    
                    if ($s && $s > 0 && $s < $p) {
                        $basePrice = $s;
                    } else {
                        $basePrice = $p;
                    }
                }

                // Null kontrolü (Hata almamak için)
                $basePrice = (float)($basePrice ?? 0);

                // --- 2. KDV EKLEME (Eğer fiyatlar KDV hariç tutuluyorsa) ---
                // "Ham Fiyat: 55 | Hesaplanan: 55" ama ekranda "0.61 TL" görüyorsan
                // Bu demektir ki 55 kuruşa %10 KDV ekleniyor (55 * 1.10 = 60.5 ~ 61)
                
                if ($vatRate > 0) {
                    $basePrice = $basePrice * (1 + ($vatRate / 100));
                }

                // --- 3. FORMATLAMA (TL'ye Çevirme) ---
                // Veritabanı 0.61 TL'yi "61" (kuruş) olarak tutuyorsa 100'e bölmeliyiz.
                // Ama yanlışlıkla "0.61" (TL) tutulmuşsa dokunmamalıyız.
                
                if ($basePrice < 10) {
                    // Zaten TL cinsinden küçük bir sayı (Örn: 0.61)
                    $paytrAmount = number_format($basePrice, 2, '.', '');
                } else {
                    // Kuruş cinsinden (Örn: 61 veya 28111)
                    $paytrAmount = number_format($basePrice / 100, 2, '.', '');
                }
            @endphp

            @if((float)$paytrAmount > 0)
                <div class="mt-8">
                    <details class="group bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:shadow-md open:ring-2 open:ring-pink-100">
                        <summary class="flex items-center justify-between p-4 cursor-pointer list-none select-none bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="bg-pink-100 p-2 rounded-lg text-pink-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900">Taksit Seçenekleri</h3>
                                    {{-- Debug: Doğru fiyatı görüp görmediğimizi kontrol edelim (Sonra silebilirsin) --}}
                                    {{-- <p class="text-xs text-gray-400">Hesaplanan Tutar: {{ $paytrAmount }} TL</p> --}}
                                    <p class="text-xs text-gray-500">Kartlara özel taksit oranlarını görmek için tıklayın</p>
                                </div>
                            </div>
                            <span class="transform transition-transform duration-300 group-open:rotate-180 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </summary>

                        <div class="p-4 bg-white border-t border-gray-100">
                            <div class="max-h-96 overflow-y-auto overflow-x-hidden custom-scrollbar">
                                <style>
                                    #paytr_taksit_tablosu * { box-sizing: content-box !important; }
                                    #paytr_taksit_tablosu { width: 100% !important; text-align: center; }
                                    .taksit-tablosu-wrapper {
                                        width: 100% !important; max-width: 350px !important; margin: 0 auto 15px auto !important;
                                        padding: 10px !important; border: 1px solid #e5e7eb !important; border-radius: 12px !important;
                                        background-color: #fff !important; display: block !important; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
                                    }
                                    .taksit-tutar-wrapper { display: flex !important; justify-content: space-between !important; align-items: center !important; width: 100% !important; padding: 8px 5px !important; border-bottom: 1px solid #f3f4f6 !important; margin: 0 !important; background-color: transparent !important; }
                                    .taksit-tutar-wrapper:last-child { border-bottom: none !important; }
                                    .taksit-tutari-text { float: none !important; width: auto !important; color: #6b7280 !important; font-size: 13px !important; font-weight: 500 !important; text-align: left !important; }
                                    .taksit-tutari { float: none !important; width: auto !important; color: #111827 !important; font-size: 13px !important; font-weight: bold !important; border: none !important; text-align: right !important; }
                                    .taksit-logo img { max-height: 30px !important; display: inline-block !important; }
                                </style>

                                <div id="paytr_taksit_tablosu"></div>
                                <script src="https://www.paytr.com/odeme/taksit-tablosu/v2?token=26ffd86457fdc453f9dc7b88915378564946da40957a710228945a468bee45d8&merchant_id=527634&amount={{ $paytrAmount }}&taksit=0&tumu=0"></script>
                            
                            </div>
                        </div>
                    </details>
                </div>
            @endif
        </div>
    </div>
</div>
