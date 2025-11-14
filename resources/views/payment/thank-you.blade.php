<x-layouts.storefront>

    {{-- Ana içerik alanı --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">

        {{-- Beyaz kutu --}}
        <div class="bg-white shadow-lg rounded-2xl p-8 md:p-12">

            {{-- Emoji --}}
            <span class="text-7xl">🎉</span>

            {{-- Başlık --}}
            <h1 class="mt-4 text-3xl sm:text-4xl font-bold text-gray-900 bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent">
                Siparişiniz Alındı!
            </h1>

            <p class="mt-4 text-lg text-gray-700">
                Teşekkür ederiz! Siparişiniz başarıyla oluşturuldu.
            </p>

            {{-- Sipariş Numarası (Rotadan gelen) --}}
            @if(isset($order_id))
                <p class="mt-2 text-sm text-gray-500">
                    Sipariş Numaranız: <strong class="text-gray-900">#{{ $order_id }}</strong>
                </p>
            @endif

            {{-- Ana Sayfa Butonu --}}
            <div class="mt-8">
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