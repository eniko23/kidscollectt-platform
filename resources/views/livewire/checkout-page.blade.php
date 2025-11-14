<div>
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Ödeme</h1>

    @if(empty($items))
        {{-- Sepet boş uyarısı (değişiklik yok) --}}
        <div class="text-center py-12">
            <p class="text-gray-600">Sepetiniz boş.</p>
            <a href="{{ route('products.index') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">
                Alışverişe Başla
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Sol Taraf: Formlar (değişiklik yok) --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Giriş Seçenekleri --}}
                @if(!Auth::check())
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Giriş Yap veya Misafir Olarak Devam Et</h2>
                        <div class="space-y-3">
                            <a href="{{ route('login') }}" class="block w-full text-center bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 font-medium">
                                Giriş Yap
                            </a>
                            <a href="{{ route('register') }}" class="block w-full text-center bg-gray-200 text-gray-900 py-3 rounded-md hover:bg-gray-300 font-medium">
                                Kayıt Ol
                            </a>
                            <div class="text-center text-sm text-gray-600">
                                veya
                            </div>
                            <button wire:click="$set('checkoutType', 'guest')" 
                                    class="block w-full text-center border border-gray-300 text-gray-700 py-3 rounded-md hover:bg-gray-50 font-medium">
                                Misafir Olarak Devam Et
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Teslimat Adresi (değişiklik yok) --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Teslimat Adresi</h2>
                    
                    @if(Auth::check() && count($savedAddresses) > 0)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kayıtlı Adreslerim</label>
                            <select wire:model.live="shippingAddressId" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Yeni Adres Ekle</option>
                                @foreach($savedAddresses as $address)
                                    <option value="{{ $address->id }}">{{ $address->label ?? ($address->first_name . ' ' . $address->last_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ad *</label>
                            <input type="text" wire:model="shippingFirstName" class="w-full border-gray-300 rounded-md shadow-sm">
                            @error('shippingFirstName') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Soyad *</label>
                            <input type="text" wire:model="shippingLastName" class="w-full border-gray-300 rounded-md shadow-sm">
                            @error('shippingLastName') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telefon *</label>
                            <input type="tel" wire:model="shippingPhone" class="w-full border-gray-300 rounded-md shadow-sm">
                            @error('shippingPhone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Adres Satırı 1 *</label>
                            <input type="text" wire:model="shippingAddressLine1" class="w-full border-gray-300 rounded-md shadow-sm">
                            @error('shippingAddressLine1') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Adres Satırı 2</label>
                            <input type="text" wire:model="shippingAddressLine2" class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">İlçe / Semt *</label>
                            <input type="text" wire:model="shippingDistrict" class="w-full border-gray-300 rounded-md shadow-sm">
                            @error('shippingDistrict') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Şehir *</label>
                            <input type="text" wire:model="shippingCity" class="w-full border-gray-300 rounded-md shadow-sm">
                            @error('shippingCity') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ülke</label>
                            <input type="text" wire:model="shippingCountry" class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>
                </div>

                {{-- Fatura Adresi (değişiklik yok) --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Fatura Adresi</h2>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model.live="sameAsShipping" class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Teslimat adresi ile aynı</span>
                        </label>
                    </div>

                    @if(!$sameAsShipping)
                        @if(Auth::check() && count($savedAddresses) > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kayıtlı Adreslerim</label>
                                <select wire:model.live="billingAddressId" class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Yeni Adres Ekle</option>
                                    @foreach($savedAddresses as $address)
                                        <option value="{{ $address->id }}">{{ $address->label ?? ($address->first_name . ' ' . $address->last_name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Fatura formu alanları (değişiklik yok) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ad *</label>
                                <input type="text" wire:model="billingFirstName" class="w-full border-gray-300 rounded-md shadow-sm">
                                @error('billingFirstName') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Soyad *</label>
                                <input type="text" wire:model="billingLastName" class="w-full border-gray-300 rounded-md shadow-sm">
                                @error('billingLastName') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Telefon *</label>
                                <input type="tel" wire:model="billingPhone" class="w-full border-gray-300 rounded-md shadow-sm">
                                @error('billingPhone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Adres Satırı 1 *</label>
                                <input type="text" wire:model="billingAddressLine1" class="w-full border-gray-300 rounded-md shadow-sm">
                                @error('billingAddressLine1') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Adres Satırı 2</label>
                                <input type="text" wire:model="billingAddressLine2" class="w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">İlçe / Semt *</label>
                                <input type="text" wire:model="billingDistrict" class="w-full border-gray-300 rounded-md shadow-sm">
                                @error('billingDistrict') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Şehir *</label>
                                <input type="text" wire:model="billingCity" class="w-full border-gray-300 rounded-md shadow-sm">
                                @error('billingCity') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ülke</label>
                                <input type="text" wire:model="billingCountry" class="w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ❗❗ ÖDEME YÖNTEMİ BURADA GÜNCELLENDİ ❗❗ --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Ödeme Yöntemi</h2>
                    <div class="space-y-4">
                        
                        {{-- 1. Kredi Kartı Seçeneği --}}
                        <label for="payment-card" 
                               class="relative flex cursor-pointer rounded-lg border p-4 shadow-sm transition-all
                                      {{ $paymentMethod === 'credit_card' ? 'border-pink-500 ring-2 ring-pink-500' : 'border-gray-300' }}">
                            
                            {{-- 
                                1. DÜZELTME: wire:model.live="paymentMethod" (daha hızlı tepki için .live)
                                2. DÜZELTME: name="payment_method" (Aynı grupta olmaları için)
                            --}}
                            <input 
                                type="radio" 
                                id="payment-card"
                                name="payment_method" 
                                wire:model.live="paymentMethod"
                                value="credit_card"
                                class="h-4 w-4 mt-1 border-gray-300 text-pink-600 focus:ring-pink-500">
                                
                            <div class="ml-3 flex flex-col">
                                <span class="block text-sm font-bold text-gray-900">
                                    Kredi Kartı / Banka Kartı
                                </span>
                                <span class="block text-sm text-gray-600">
                                    3D Secure ile güvenli ödeme
                                </span>
                            </div>
                        </label>
                
                        {{-- 2. Kapıda Ödeme Seçeneği --}}
                        <label for="payment-cod" 
                               class="relative flex cursor-pointer rounded-lg border p-4 shadow-sm transition-all
                                      {{ $paymentMethod === 'cash_on_delivery' ? 'border-pink-500 ring-2 ring-pink-500' : 'border-gray-300' }}">
                            
                            {{-- 
                                1. DÜZELTME: wire:model.live="paymentMethod"
                                2. DÜZELTME: name="payment_method"
                            --}}
                            <input 
                                type="radio" 
                                id="payment-cod"
                                name="payment_method" 
                                wire:model.live="paymentMethod"
                                value="cash_on_delivery"
                                class="h-4 w-4 mt-1 border-gray-300 text-pink-600 focus:ring-pink-500">
                                
                            <div class="ml-3 flex flex-col">
                                <span class="block text-sm font-bold text-gray-900">
                                    Kapıda Ödeme
                                </span>
                                <span class="block text-sm text-gray-600">
                                    Teslimat sırasında nakit veya kredi kartı ile ödeme
                                </span>
                            </div>
                        </label>
                        
                    </div>
                </div>
                {{-- ❗❗ GÜNCELLEME BİTTİ ❗❗ --}}
            </div>

            {{-- Sağ Taraf: Özet (değişiklik yok) --}}
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-6 sticky top-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Sipariş Özeti</h2>
                    
                    <div class="space-y-2 mb-4">
                        @foreach($items as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ $item['variant']->product->name }} ({{ $item['quantity'] }}x)</span>
                                <span class="text-gray-900 font-medium">{{ number_format($item['subtotal'] / 100, 2, ',', '.') }} TL</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-200 pt-4 space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Ara Toplam</span>
                            <span class="text-gray-900 font-medium">{{ number_format($subtotal / 100, 2, ',', '.') }} TL</span>
                        </div>
                        
                        {{-- İNDİRİM SATIRI --}}
                        @if($discount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-green-600 font-medium">
                                    İndirim ({{ $appliedCoupon->code }})
                                </span>
                                <span class="text-green-600 font-medium">
                                    - {{ number_format($discount / 100, 2, ',', '.') }} TL
                                </span>
                            </div>
                        @endif

                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Kargo</span>
                            <span class="text-gray-900 font-medium">
                                @if($shipping == 0)
                                    <span class="text-green-600">Ücretsiz</span>
                                @else
                                    {{ number_format($shipping / 100, 2, ',', '.') }} TL
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">KDV</span>
                            <span class="text-gray-900 font-medium">{{ number_format($tax / 100, 2, ',', '.') }} TL</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between text-lg font-bold">
                            <span class="text-gray-900">Toplam</span>
                            <span class="text-gray-900">{{ number_format($total / 100, 2, ',', '.') }} TL</span>
                        </div>
                    </div>

                    <button wire:click="submitOrder" 
                            class="w-full bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 font-medium">
                        Siparişi Onayla
                    </button>
                    
                    @if(session('error_checkout'))
                        <div class="mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                            <p class="font-bold">Sipariş Oluşturulamadı</p>
                            <p>{{ session('error_checkout') }}</p>
                        </div>
                    @endif

                    <a href="{{ route('cart.index') }}" 
                       class="block w-full text-center mt-3 text-gray-600 hover:text-gray-900">
                        Sepete Dön
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>