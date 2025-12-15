@php
    // Veritabanından SADECE ana kategorileri (parent_id'si olmayanları) çek
    $categories = \App\Models\Category::whereNull('parent_id')
                                        ->orderBy('name')
                                        ->get();
@endphp

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Kids Collectt' }}</title>

    {{-- ❗ YENİ FONT (BeeShop'taki gibi) ❗ --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- ❗ YENİ FONT BİTTİ ❗ --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    {{-- Livewire Debug (Geliştirme için) --}}
    @if(config('app.debug'))
        <script>
            document.addEventListener('livewire:init', () => {
                console.log('✅ Livewire initialized!');
            });
            
            document.addEventListener('livewire:error', (event) => {
                console.error('❌ Livewire error:', event.detail);
            });
        </script>
    @endif
    
    <style>
        /* Yazı gölgesi (footer'daki resim için) */
        .text-shadow {
            text-shadow: 0 1px 3px rgb(0 0 0 / 0.4);
        }

        /* ❗ YENİ FONT CLASS'LARI ❗ */
        .font-brand {
            font-family: 'Fredoka', sans-serif; /* Çocuksu, eğlenceli font */
            letter-spacing: 0.5px;
        }
        .font-body {
            font-family: 'Inter', sans-serif; /* Okunaklı gövde fontu */
        }
        body {
            font-family: 'Inter', sans-serif; /* Varsayılan font */
        }
    </style>
</head>
<body x-data="{ mobileMenuOpen: false }" class="font-body antialiased bg-gradient-to-b from-pink-50 via-purple-50 to-blue-50 min-h-screen">

    {{-- ❗ YENİ ARKA PLAN DESENİ ❗ --}}
    {{-- Bu div, o boş gradyanın üzerine hafif noktalı bir desen ekler --}}
    <div class="fixed inset-0 w-full h-full opacity-[0.03] z-0" style="background-image: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"20\" height=\"20\" viewBox=\"0 0 20 20\"><circle fill=\"%23DB2777\" cx=\"10\" cy=\"10\" r=\"1\"></circle></svg>');"></div>
    {{-- ❗ YENİ DESEN BİTTİ ❗ --}}

    {{-- Header'ın 'relative' ve 'z-50' olması, desenin arkada kalmasını sağlar --}}
    <header 
        class="bg-white/95 backdrop-blur-sm shadow-lg sticky top-0 z-40 border-b-4 border-pink-300"
    >
        <nav class="container mx-auto px-4 sm:px-6 lg:px-8" aria-label="Top">
            <div class="flex items-center justify-between h-20">

                {{-- Hamburger Menü Butonu (Mobil) --}}
                <div class="flex items-center lg:hidden mr-4">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="text-gray-600 hover:text-pink-600 focus:outline-none p-2 rounded-md hover:bg-pink-50 transition-colors">
                        <span class="sr-only">Menüyü Aç</span>
                        <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
                
                {{-- Logo (Animasyonlu) --}}
                <div class="flex-1 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        {{-- ❗ YENİ ANİMASYON ❗ (group-hover:animate-bounce) --}}
                        <img src="{{ asset('images/logo.png') }}" alt="Kids Collectt Logo" class="h-10 w-auto transition-transform duration-300 ease-out group-hover:scale-110"> {{-- Buradaki 'logo.png' kısmını kendi dosya adınızla değiştirin --}}
                        <span class="text-2xl sm:text-3xl font-bold font-brand bg-gradient-to-r from-pink-500 via-purple-500 to-pink-500 bg-clip-text text-transparent group-hover:from-pink-600 group-hover:via-purple-600 group-hover:to-pink-600 transition-all duration-300">
                            KIDS COLLECTT
                        </span>
                    </a>
                </div>

                {{-- Sağ Menü (Giriş/Kayıt/Hesabım/Sepet) --}}
                <div class="flex items-center space-x-3 sm:space-x-4">
                        
                    @auth
                        {{-- 1. KULLANICI GİRİŞ YAPMIŞSA --}}
                        <a 
                            href="{{ route('dashboard') }}"
                            class="p-2 text-gray-600 hover:text-pink-600 hover:bg-pink-50 rounded-full transition-all duration-200 hover:scale-110"
                            title="Hesabım"
                        >
                            <span class="sr-only">Hesabım</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </a>

                        {{-- Çıkış Yap Formu --}}
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button 
                                type="submit" 
                                class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-full transition-all duration-200 hover:scale-110"
                                title="Çıkış Yap"
                            >
                                <span class="sr-only">Çıkış Yap</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                </svg>
                            </button>
                        </form>

                    @else
                        {{-- 2. MİSAFİR KULLANICI --}}
                        <div 
                            x-data="{ open: false }" 
                            @mouseenter="open = true" 
                            @mouseleave="open = false" 
                            class="relative"
                        >
                            <button 
                                type="button"
                                @click.prevent="open = !open" 
                                @keydown.escape.window="open = false"
                                class="p-2 text-gray-600 hover:text-pink-600 hover:bg-pink-50 rounded-full transition-all duration-200 hover:scale-110"
                                aria-label="Hesap menüsü"
                            >
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </button>
                            
                            <div 
                                x-show="open" 
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                style="display: none;"
                                class="absolute right-0 mt-2 w-48 origin-top-right z-50"
                            >
                                <div class="absolute -top-2 left-1/2 -ml-2 w-4 h-4" style="left: 85%;">
                                    <div class="w-full h-full bg-white transform rotate-45 border-t border-l" style="border-color: rgba(0,0,0,0.05);"></div>
                                </div>
                                <div class="relative bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-2">
                                    <a href="{{ route('login') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-700 font-medium transition-colors duration-150">
                                        <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                        </svg>
                                        <span class="font-bold">Giriş</span>
                                    </a>
                                    <a href="{{ route('register') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-700 font-medium transition-colors duration-150">
                                        <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                        <span class="font-bold">Üye Ol</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endauth
                        
                    {{-- Favoriler İkonu --}}
                    <a href="#" class="p-2 text-gray-600 hover:text-red-500 hover:bg-red-50 rounded-full transition-all duration-200 hover:scale-110">
                        <span class="sr-only">Favoriler</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.5l1.318-1.182a4.5 4.5 0 116.364 6.364L12 20.06l-7.682-7.682a4.5 4.5 0 010-6.364z" /></svg>
                    </a>
                        
                    {{-- Sepet İkonu --}}
                    <div class="relative">
                        @livewire('cart-counter')
                    </div>
                </div>

            </div>
            
            {{-- Kategori Menüsü (İkonlu) --}}
            <div class="flex justify-center py-3 border-t-2 border-pink-100 hidden lg:flex">
                <div class="flex flex-wrap justify-center gap-4 sm:gap-6">
                    @if(isset($categories) && $categories->count() > 0)
                        @foreach($categories as $category)
                            <a href="{{ route('category.show', $category) }}" 
                               class="flex items-center gap-2 text-sm sm:text-base font-bold text-gray-700 hover:text-pink-600 px-4 py-2 rounded-full hover:bg-pink-50 transition-all duration-200 hover:scale-105">
                                
                                {{-- ❗ YENİ İKONLAR ❗ (Kategorilerine göre) --}}
                                @if(Str::contains($category->name, ['Kız', 'Kadın']))
                                    <span class="text-pink-500">♀</span>
                                @elseif(Str::contains($category->name, ['Erkek']))
                                    <span class="text-blue-500">♂</span>
                                @elseif(Str::contains($category->name, ['Bebek']))
                                    <span class="text-purple-500">🍼</span>
                                @else
                                    <span class="text-gray-400">🏷️</span>
                                @endif
                                {{-- ❗ İKONLAR BİTTİ ❗ --}}

                                {{ $category->name }}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </nav>

    </header>

    {{-- MOBİL MENÜ (DRAWER) - Header Dışına Taşındı --}}
    <div
        x-show="mobileMenuOpen"
        class="fixed inset-0 z-[999] flex lg:hidden"
        role="dialog"
        aria-modal="true"
        style="display: none;"
    >
        {{-- Arkaplan Karartma --}}
        <div 
            x-show="mobileMenuOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm"
            @click="mobileMenuOpen = false"
        ></div>

        {{-- Yan Menü Paneli --}}
        <div
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="relative flex-1 flex flex-col max-w-xs w-full bg-white shadow-xl h-full overflow-y-auto"
            style="background-color: white !important;"
        >
            {{-- Menü Başlığı ve Kapatma Butonu --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-pink-100 bg-pink-50" style="background-color: #fdf2f8;">
                <span class="text-xl font-brand font-bold text-pink-600">Menü</span>
                <button @click="mobileMenuOpen = false" type="button" class="-mr-2 p-2 rounded-md text-gray-500 hover:text-pink-600 hover:bg-white transition-colors">
                    <span class="sr-only">Menüyü Kapat</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Menü Linkleri --}}
            <nav class="px-4 py-6 space-y-2 bg-white flex-1" style="background-color: white;">

                {{-- Ana Kategoriler (Sidebar'dan Kopyalandı) --}}
                <a href="{{ route('products.index') }}" class="flex items-center px-4 py-3 text-base font-medium text-gray-700 hover:text-pink-600 hover:bg-pink-50 rounded-lg transition-colors group">
                    <span class="w-8 h-8 mr-3 rounded-full bg-gray-100 flex items-center justify-center text-lg group-hover:bg-pink-200 transition-all">🛍️</span>
                    Tüm Ürünler
                </a>
                <a href="/kategori/en-cok-satanlar" class="flex items-center px-4 py-3 text-base font-medium text-gray-700 hover:text-pink-600 hover:bg-pink-50 rounded-lg transition-colors group">
                    <span class="w-8 h-8 mr-3 rounded-full bg-orange-100 flex items-center justify-center text-lg group-hover:bg-orange-200 transition-all">🔥</span>
                    En Çok Satanlar
                </a>
                <a href="/kategori/erkek-giyim" class="flex items-center px-4 py-3 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors group">
                    <span class="w-8 h-8 mr-3 rounded-full bg-blue-100 flex items-center justify-center text-lg group-hover:bg-blue-200 transition-all">👦</span>
                    Erkek Çocuk
                </a>
                <a href="/kategori/kiz-cocuk" class="flex items-center px-4 py-3 text-base font-medium text-gray-700 hover:text-pink-600 hover:bg-pink-50 rounded-lg transition-colors group">
                    <span class="w-8 h-8 mr-3 rounded-full bg-pink-100 flex items-center justify-center text-lg group-hover:bg-pink-200 transition-all">👧</span>
                    Kız Çocuk
                </a>
                <a href="/kategori/yetiskin" class="flex items-center px-4 py-3 text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors group">
                    <span class="w-8 h-8 mr-3 rounded-full bg-purple-100 flex items-center justify-center text-lg group-hover:bg-purple-200 transition-all">👩</span>
                    Yetişkin
                </a>
                <a href="/kategori/aile-kombinleri" class="flex items-center px-4 py-3 text-base font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors group">
                    <span class="w-8 h-8 mr-3 rounded-full bg-green-100 flex items-center justify-center text-lg group-hover:bg-green-200 transition-all">👨‍👩‍👧‍👦</span>
                    Aile Kombinleri
                </a>
                <a href="/kategori/indirimdekiler" class="flex items-center px-4 py-3 text-base font-medium text-red-700 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors group">
                    <span class="w-8 h-8 mr-3 rounded-full bg-red-100 flex items-center justify-center text-lg group-hover:bg-red-200 transition-all">🏷️</span>
                    İndirimdekiler
                </a>

                <div class="border-t border-gray-100 my-4"></div>

                {{-- Diğer Kategoriler (Dynamic) --}}
                @if(isset($categories) && $categories->count() > 0)
                    <div class="px-4 py-2">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kategoriler</span>
                    </div>
                    @foreach($categories as $category)
                            <a href="{{ route('category.show', $category) }}" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-pink-600 hover:bg-pink-50 rounded-lg transition-colors">
                            {{ $category->name }}
                        </a>
                    @endforeach
                @endif

            </nav>

            {{-- Alt Kısım (Footer-like) --}}
            <div class="mt-auto border-t border-gray-200 p-6 bg-gray-50">
                <p class="text-sm text-center text-gray-500">
                    &copy; {{ date('Y') }} Kids Collectt
                </p>
            </div>
        </div>
    </div>

    {{-- Ana içerik, desenin üzerinde kalması için 'relative z-10' --}}
    <main class="min-h-screen relative z-10">
        {{ $slot }}
    </main>

    {{-- Footer (Resimli) --}}
    <footer 
        class="text-black border-t-4 border-pink-300 mt-12 
               bg-cover bg-center bg-no-repeat" 
        style="background-image: url('{{ asset('images/footer-bg.jpg') }}');"
    >
        {{-- Siyah katman (overlay) kaldırıldı --}}
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    {{-- 
                        'text-shadow' class'ı, resmin önündeki yazıların 
                        okunaklı olmasını sağlar. (Head içindeki <style> etiketinden gelir)
                    --}}
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-shadow">
                        <span>🏢</span>
                        <span>Kurumsal</span>
                    </h3>
                    <ul role="list" class="space-y-2">
                        <li><a href="#" class="text-sm hover:text-yellow-200 transition-colors duration-200 text-shadow">Hakkımızda</a></li>
                        <li><a href="#" class="text-sm hover:text-yellow-200 transition-colors duration-200 text-shadow">İletişim</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-shadow">
                        <span>💝</span>
                        <span>Yardım</span>
                    </h3>
                    <ul role="list" class="space-y-2">
                        <li><a href="#" class="text-sm hover:text-yellow-200 transition-colors duration-200 text-shadow">Sipariş Takibi</a></li>
                        <li><a href="#" class="text-sm hover:text-yellow-200 transition-colors duration-200 text-shadow">İade ve Değişim</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t-2 border-black border-opacity-30 pt-8 text-center">
                <p class="text-sm font-medium text-shadow">&copy; {{ date('Y') }} Kids Collectt. Tüm hakları saklıdır. 🎈</p>
            </div>
        </div>
    </footer>

    {{-- Sepete Ekleme Bildirimi --}}
    <div 
        x-data="{ show: false, message: '' }"
        x-on:product-added-to-cart.window="
            message = `Ürün ('${event.detail.variant_name}') sepete eklendi!`;
            show = true;
            setTimeout(() => show = false, 3000); // 3 saniye sonra mesajı gizle
        "
        x-show="show"
        x-transition
        style="display: none;"
        class="fixed bottom-4 right-4 z-50 rounded-md bg-green-600 px-4 py-3 text-white shadow-lg"
    >
        <p x-text="message"></p>
    </div>

    @livewireScriptConfig

</body>
</html>