<x-layouts.storefront>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">

        <div class="bg-white shadow-lg rounded-2xl p-8 md:p-12">

            <span class="text-7xl">🎉</span>

            <h1 class="mt-4 text-3xl sm:text-4xl font-bold text-gray-900 bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent">
                Siparişiniz Alındı!
            </h1>

            <p class="mt-4 text-lg text-gray-700">
                Teşekkür ederiz! Siparişiniz başarıyla oluşturuldu.
            </p>

            {{-- Sipariş Numarası --}}
            @if(isset($order_id))
                <div class="mt-6 bg-gray-50 p-4 rounded-lg inline-block border border-gray-200">
                    <p class="text-sm text-gray-500">Sipariş Numaranız</p>
                    <p class="text-2xl font-bold text-gray-900">#{{ $order_id }}</p>
                </div>
            @endif

            {{-- 
                ❗❗ YENİ EKLENEN İADE BİLGİSİ BURADA ❗❗
                Sipariş numarasının hemen altına ekledik.
            --}}
            <div class="mt-8 flex flex-col items-center justify-center space-y-2">
                <div class="flex items-center gap-3 bg-green-50 px-6 py-3 rounded-full border border-green-100 shadow-sm">
                    {{-- İade İkonu (Kamyon/Kutu) --}}
                    <img src="https://img.icons8.com/?size=100&id=12391&format=png&color=000000" 
                         alt="İade Garantisi" 
                         class="w-8 h-8 opacity-70">
                    
                    <div class="text-left">
                        <p class="text-sm font-bold text-green-800">İade & Değişim Garantisi</p>
                        <p class="text-xs text-green-600">Beğenmezseniz 14 gün içinde koşulsuz iade edebilirsiniz.</p>
                    </div>
                </div>
            </div>
            {{-- ❗❗ EKLEME BİTTİ ❗❗ --}}

            {{-- Ana Sayfa Butonu --}}
            <div class="mt-10">
                <a href="{{ route('home') }}" 
                   class="inline-block px-8 py-3 rounded-lg text-base font-bold text-white transition-all duration-300 transform hover:scale-105 hover:shadow-2xl
                          bg-gradient-to-r from-pink-500 via-purple-500 to-pink-500 
                          hover:from-pink-600 hover:via-purple-600 hover:to-pink-600 
                          shadow-lg cursor-pointer">
                    Alışverişe Devam Et
                </a>
            </div>

        </div>
    </div>
</x-layouts.storefront>