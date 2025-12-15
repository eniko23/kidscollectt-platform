@props(['product', 'showBadge' => false])
{{-- 
    'showBadge' => false  -> Bu, 'show-badge' ayarı verilmezse 
                          varsayılan olarak etiketi GİZLE demektir. 
--}}

<div class="group relative bg-white rounded-xl shadow-lg hover:shadow-2xl overflow-hidden transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
    <a href="{{ route('products.show', $product) }}">
        <div class="aspect-square w-full overflow-hidden relative">
            
            {{-- Ürün Resmi --}}
            @if($product->getFirstMedia('product-images'))
                <img src="{{ $product->getFirstMedia('product-images')->getUrl() }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
            @else
                {{-- Resim Yoksa Göster --}}
                <div class="h-full w-full flex items-center justify-center bg-gray-100">
                    <svg class="h-16 w-16 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                </div>
            @endif

            {{-- Yeni Etiketi (Değişiklik yok) --}}
            @if($showBadge)
                <span class="absolute top-3 left-3 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full">YENİ</span>
            @endif
        </div>
        
        {{-- Ürün Bilgileri (FİYAT KISMI GÜNCELLENDİ) --}}
        <div class="p-5 text-center">
            <h3 class="text-md font-bold text-gray-800 mb-2 truncate" title="{{ $product->name }}">
                {{ $product->name }}
            </h3>
            
            {{-- === YENİ FİYAT BLOKU (BURASI DEĞİŞTİ) === --}}
            @php
                use App\Support\VatCalculator;

                // Stokta olan varyantları bul
                $inStockVariants = $product->variants()->where('stock', '>', 0);
                
                // Stoktakilerin en düşük normal fiyatını bul
                $minPriceInStock = $inStockVariants->min('price');
                
                // Stoktakilerin en düşük indirimli fiyatını bul (0'dan büyük olmalı)
                $minSalePriceInStock = $product->variants()
                                    ->where('stock', '>', 0)
                                    ->whereNotNull('sale_price')
                                    ->where('sale_price', '>', 0)
                                    ->min('sale_price');
                
                $displayPrice = null;
                $displayOldPrice = null;

                if ($minPriceInStock !== null) { // Stokta en az bir varyant varsa
                    
                    // KDV Hesaplaması
                    $vatRate = $product->vat_rate ?? 0;
                    if ($vatRate > 0) {
                        $minPriceInStock = VatCalculator::calculate($minPriceInStock, $vatRate);
                        if ($minSalePriceInStock) {
                            $minSalePriceInStock = VatCalculator::calculate($minSalePriceInStock, $vatRate);
                        }
                    }

                    $priceToShow = $minSalePriceInStock ?? $minPriceInStock;
                    
                    // Hatalı girişi engelle: İndirimli fiyat normalden yüksekse, normal fiyatı göster
                    if ($minSalePriceInStock && $minPriceInStock && $minSalePriceInStock >= $minPriceInStock) {
                        $priceToShow = $minPriceInStock;
                    }

                    // Gösterilecek fiyatı formatla
                    $displayPrice = number_format($priceToShow / 100, 2, ',', '.');
                    
                    // Gerçek bir indirim olup olmadığını kontrol et
                    if ($minSalePriceInStock && $minPriceInStock && $minSalePriceInStock < $minPriceInStock) {
                        // Varsa, eski fiyatı (normal fiyat) formatla
                        $displayOldPrice = number_format($minPriceInStock / 100, 2, ',', '.');
                    }
                }
            @endphp

            {{-- 
                HTML Çıktısı: 
                'min-h-[3.5rem]' sınıfı, iki satırlık fiyat alanını kapsar.
                Flex column yapısı ile fiyatlar alt alta ortalanır.
            --}}
            <p class="mt-3 text-xl font-extrabold text-gray-900 min-h-[3.5rem] flex flex-col items-center justify-center">
                @if($displayPrice)
                    {{-- İNDİRİM VARSA (Eski fiyat doluysa) --}}
                    @if($displayOldPrice)
                        <span class="text-pink-600">
                            {{ $displayPrice }} TL
                        </span>
                        <span class="text-gray-400 line-through text-base">
                            {{ $displayOldPrice }} TL
                        </span>
                    @else
                        {{-- İNDİRİM YOKSA (Sadece normal fiyat) --}}
                        <span class="text-gray-900">
                            {{ $displayPrice }} TL
                        </span>
                    @endif
                @else
                    {{-- STOKTA YOKSA ($displayPrice = null ise) --}}
                    <span class="text-gray-500 font-medium text-md">Stokta Yok</span>
                @endif
            </p>
            {{-- === FİYAT BLOKU BİTTİ === --}}

        </div>
    </a>
</div>
