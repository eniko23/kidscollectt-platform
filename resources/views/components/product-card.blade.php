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
            
            {{-- === YENİ FİYAT BLOKU (DAHA SAĞLAM MANTIK) === --}}
            @php
                use App\Support\VatCalculator;

                // 1. Stokta olan tüm varyantları çekiyoruz
                // Eager loaded ise veritabanı sorgusu yapmaz.
                $variants = $product->variants->where('stock', '>', 0);
                
                $bestPrice = null;     // Gösterilecek nihai fiyat
                $bestOldPrice = null;  // Gösterilecek üstü çizili fiyat (varsa)

                if ($variants->isNotEmpty()) {
                    $lowestEffectivePrice = null;
                    $selectedVariant = null;

                    // 2. En ucuz varyantı bul (İndirimli fiyatı varsa onu, yoksa normal fiyatı baz al)
                    foreach ($variants as $variant) {
                        $price = (int) $variant->price;
                        $salePriceRaw = $variant->sale_price;

                        // İndirim geçerli mi? (null değil, 0'dan büyük, normal fiyattan küçük)
                        $hasDiscount = (!is_null($salePriceRaw) && (int)$salePriceRaw > 0 && (int)$salePriceRaw < $price);

                        $salePrice = $hasDiscount ? (int)$salePriceRaw : null;

                        // Bu varyantın müşteriye maliyeti nedir?
                        $effectivePrice = $salePrice ?? $price;

                        if ($lowestEffectivePrice === null || $effectivePrice < $lowestEffectivePrice) {
                            $lowestEffectivePrice = $effectivePrice;
                            $selectedVariant = $variant;
                        }
                    }

                    // 3. Seçilen varyantın fiyatlarını hazırla
                    if ($selectedVariant) {
                        $vatRate = $product->vat_rate ?? 0;

                        $rawPrice = (int) $selectedVariant->price;
                        $rawSalePriceVal = $selectedVariant->sale_price;

                        // Tekrar kontrol: İndirim geçerli mi?
                        $hasDiscount = (!is_null($rawSalePriceVal) && (int)$rawSalePriceVal > 0 && (int)$rawSalePriceVal < $rawPrice);
                        $rawSalePrice = $hasDiscount ? (int)$rawSalePriceVal : null;

                        // KDV Ekle
                        if ($vatRate > 0) {
                            $rawPrice = VatCalculator::calculate($rawPrice, $vatRate);
                            if ($rawSalePrice) {
                                $rawSalePrice = VatCalculator::calculate($rawSalePrice, $vatRate);
                            }
                        }

                        // Gösterilecek fiyatları ayarla
                        if ($rawSalePrice) {
                            // İndirim VAR
                            $bestPrice = number_format($rawSalePrice / 100, 2, ',', '.');
                            $bestOldPrice = number_format($rawPrice / 100, 2, ',', '.');
                        } else {
                            // İndirim YOK
                            $bestPrice = number_format($rawPrice / 100, 2, ',', '.');
                        }
                    }
                }
            @endphp

            {{-- 
                HTML Çıktısı: 
                'min-h-[3.5rem]' sınıfı, iki satırlık fiyat alanını kapsar.
                Flex column yapısı ile fiyatlar alt alta ortalanır.
            --}}
            <p class="mt-3 text-xl font-extrabold text-gray-900 min-h-[3.5rem] flex flex-col items-center justify-center">
                @if($bestPrice)
                    {{-- İNDİRİM VARSA (Eski fiyat doluysa) --}}
                    @if($bestOldPrice)
                        <span class="text-pink-600 block">
                            {{ $bestPrice }} TL
                        </span>
                        <span class="text-gray-400 line-through text-sm block">
                            {{ $bestOldPrice }} TL
                        </span>
                    @else
                        {{-- İNDİRİM YOKSA (Sadece normal fiyat) --}}
                        <span class="text-gray-900">
                            {{ $bestPrice }} TL
                        </span>
                    @endif
                @else
                    {{-- STOKTA YOKSA ($bestPrice = null ise) --}}
                    <span class="text-gray-500 font-medium text-md">Stokta Yok</span>
                @endif
            </p>
            {{-- === FİYAT BLOKU BİTTİ === --}}

        </div>
    </a>
</div>
