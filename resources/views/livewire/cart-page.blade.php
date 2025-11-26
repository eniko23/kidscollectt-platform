<div>
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Sepetim</h1>

    @if(count($items) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Sepet İçeriği (Sol Taraf) --}}
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg divide-y divide-gray-200">
                    @foreach($items as $item)
                        <div class="p-6 flex items-center space-x-4">
                            {{-- Ürün Resmi --}}
                            <div class="flex-shrink-0">
                                @if($item['variant']->product->getFirstMedia('product-images'))
                                    <img src="{{ $item['variant']->product->getFirstMedia('product-images')->getUrl() }}" 
                                         alt="{{ $item['variant']->product->name }}" 
                                         class="h-24 w-24 object-cover rounded-lg">
                                @else
                                    <div class="h-24 w-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Ürün Bilgileri --}}
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-medium text-gray-900">
                                    <a href="{{ route('products.show', $item['variant']->product) }}">
                                        {{ $item['variant']->product->name }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Beden: {{ $item['variant']->size }} | 
                                    Renk: {{ $item['variant']->color_name }}
                                </p>
                                <p class="text-lg font-semibold text-gray-900 mt-2">
                                    {{ number_format($item['subtotal'] / 100, 2, ',', '.') }} TL
                                </p>
                            </div>

                            {{-- Adet Kontrolü --}}
                            <div class="flex items-center space-x-2">
                                <button 
                                    wire:click="decrementQuantity({{ $item['variant_id'] }})"
                                    class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                    -
                                </button>
                                <span class="w-12 text-center font-medium">{{ $item['quantity'] }}</span>
                                <button 
                                    wire:click="incrementQuantity({{ $item['variant_id'] }})"
                                    @if($item['quantity'] >= $item['variant']->stock) disabled @endif
                                    class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 disabled:opacity-50">
                                    +
                                </button>
                            </div>

                            {{-- Kaldır Butonu --}}
                            <button 
                                wire:click="removeItem({{ $item['variant_id'] }})"
                                class="text-red-600 hover:text-red-800 ml-4">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Özet Kutusu (Sağ Taraf) --}}
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-6 sticky top-4 space-y-4">
                    <h2 class="text-lg font-semibold text-gray-900">Sipariş Özeti</h2>
                    
                    {{-- YENİ: KUPON FORMU --}}
                    <form wire:submit.prevent="applyCoupon" class="space-y-2">
                        <label for="couponCode" class="text-sm font-medium text-gray-700">İndirim Kodu</label>
                        <div class="flex gap-2">
                            <input 
                                type="text" 
                                id="couponCode"
                                wire:model.defer="couponCode" 
                                class="flex-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Kupon kodunuz"
                                @if($appliedCoupon) readonly @endif
                            >
                            @if(!$appliedCoupon)
                                <button 
                                    type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                                    Uygula
                                </button>
                            @endif
                        </div>
                    </form>
                    
                    {{-- YENİ: KUPON BİLGİ MESAJLARI --}}
                    <div>
                        @if(session('success_coupon'))
                            <p class="text-sm text-green-600 font-medium">{{ session('success_coupon') }}</p>
                        @endif
                        @if(session('error_coupon'))
                            <p class="text-sm text-red-600 font-medium">{{ session('error_coupon') }}</p>
                        @endif
                        @if(session('info_coupon'))
                            <p class="text-sm text-blue-600 font-medium">{{ session('info_coupon') }}</p>
                        @endif
                    </div>

                    {{-- GÜNCELLENDİ: Özet Detayları --}}
                    <div class="space-y-3 border-t border-gray-200 pt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Ara Toplam:</span>
                            <span class="text-gray-900 font-medium">{{ number_format($subtotal / 100, 2, ',', '.') }} TL</span>
                        </div>
                        
                        {{-- YENİ: İNDİRİM SATIRI --}}
                        @if($appliedCoupon)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">
                                    İndirim 
                                    <span class="font-bold text-green-600">({{ $appliedCoupon->code }})</span>
                                    <button wire:click="removeCoupon" class="ml-1 text-red-500 hover:text-red-700 font-bold">[Kaldır]</button>
                                </span>
                                <span class="text-green-600 font-medium">
                                    - {{ number_format($discount / 100, 2, ',', '.') }} TL
                                </span>
                            </div>
                        @endif

                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Kargo:</span>
                            <span class="text-gray-900 font-medium">
                                @if($shippingCost == 0)
                                    <span class="text-green-600">Ücretsiz</span>
                                @else
                                    {{ number_format($shippingCost / 100, 2, ',', '.') }} TL
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- KDV Bilgisi (Bilgi amaçlı, toplama dahil değil çünkü fiyatlar KDV dahil) --}}
                    <div class="flex justify-between text-gray-500 text-sm">
                            <span>KDV (%10):</span>
                            <span>{{ number_format($vat / 100, 2, ',', '.') }} TL</span>
                    </div>

                    {{-- GÜNCELLENDİ: Toplam Fiyat --}}
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between text-lg font-bold">
                            <span class="text-gray-900">Toplam</span>
                            <span class="text-gray-900">
                                {{-- $total değişkenini kullanıyoruz --}}
                                {{ number_format($total / 100, 2, ',', '.') }} TL
                            </span>
                        </div>
                    </div>

                    <button 
                    wire:click="goToCheckout" 
                    class="block w-full text-center bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 font-medium">
                    Ödemeye Geç
                    </button>

                    <a href="{{ route('products.index') }}" 
                       class="block w-full text-center mt-3 text-gray-600 hover:text-gray-900">
                        Alışverişe Devam Et
                    </a>
                </div>
            </div>
        </div>
    @else
        {{-- Sepet Boş Ekranı (Değişiklik yok) --}}
        <div class="text-center py-12">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h2 class="mt-4 text-xl font-semibold text-gray-900">Sepetiniz boş</h2>
            <p class="mt-2 text-gray-600">Sepetinize ürün eklemek için alışverişe başlayın.</p>
            <a href="{{ route('products.index') }}" 
               class="mt-6 inline-block bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 font-medium">
                Alışverişe Başla
            </a>
        </div>
    @endif
</div>