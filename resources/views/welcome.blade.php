<x-layouts.storefront>
    <x-slot:title>
        Ana Sayfa - Kids Collectt
    </x-slot:title>

    <div x-data="{ activeTab: 'new' }" class="bg-gray-100">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <aside class="lg:col-span-1 p-6 bg-white rounded-xl shadow-lg h-fit border border-gray-200 sticky top-28">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b-2 border-pink-300">
                        Kategoriler
                    </h2>
                    <nav>
                        <ul class="space-y-3">
                            <li><a href="{{ route('products.index') }}" class="flex items-center group font-medium text-gray-700 hover:text-pink-600 transition duration-200">
                                <span class="w-8 h-8 mr-3 rounded-full bg-gray-100 flex items-center justify-center text-lg group-hover:bg-gray-200 transition-all">🛍️</span>
                                Tüm Ürünler
                            </a></li>
                            <li><a href="/kategori/en-cok-satanlar" class="flex items-center group font-medium text-gray-700 hover:text-pink-600 transition duration-200">
                                <span class="w-8 h-8 mr-3 rounded-full bg-orange-100 flex items-center justify-center text-lg group-hover:bg-orange-200 transition-all">🔥</span>
                                En Çok Satanlar
                            </a></li>
                            <li><a href="/kategori/erkek-giyim" class="flex items-center group font-medium text-gray-700 hover:text-blue-600 transition duration-200">
                                <span class="w-8 h-8 mr-3 rounded-full bg-blue-100 flex items-center justify-center text-lg group-hover:bg-blue-200 transition-all">👦</span>
                                Erkek Çocuk
                            </a></li>
                            <li><a href="/kategori/kiz-cocuk" class="flex items-center group font-medium text-gray-700 hover:text-pink-600 transition duration-200">
                                <span class="w-8 h-8 mr-3 rounded-full bg-pink-100 flex items-center justify-center text-lg group-hover:bg-pink-200 transition-all">👧</span>
                                Kız Çocuk
                            </a></li>
                            <li><a href="/kategori/yetiskin" class="flex items-center group font-medium text-gray-700 hover:text-purple-600 transition duration-200">
                                <span class="w-8 h-8 mr-3 rounded-full bg-purple-100 flex items-center justify-center text-lg group-hover:bg-purple-200 transition-all">👩</span>
                                Yetişkin
                            </a></li>
                            <li><a href="/kategori/aile-kombinleri" class="flex items-center group font-medium text-gray-700 hover:text-green-600 transition duration-200">
                                <span class="w-8 h-8 mr-3 rounded-full bg-green-100 flex items-center justify-center text-lg group-hover:bg-green-200 transition-all">👨‍👩‍👧‍👦</span>
                                Aile Kombinleri
                            </a></li>
                            <li><a href="/kategori/indirimdekiler" class="flex items-center group font-medium text-red-700 hover:text-red-600 transition duration-200">
                                <span class="w-8 h-8 mr-3 rounded-full bg-red-100 flex items-center justify-center text-lg group-hover:bg-red-200 transition-all">🏷️</span>
                                İndirimdekiler
                            </a></li>
                        </ul>
                    </nav>
                </aside>
                
                <main class="lg:col-span-3 space-y-8">
                    
                    <section class="relative bg-gradient-to-r from-blue-400 to-cyan-500 h-96 rounded-xl shadow-2xl flex items-center justify-center text-white overflow-hidden group">
                        <img 
                            src="{{ asset('images/ana-banner.jpg') }}" 
                            alt="Kids Collectt Yeni Sezon" 
                            class="absolute w-full h-full object-cover transition-transform duration-500 transform group-hover:scale-110">
                        <div class="absolute inset-0 bg-black opacity-30 group-hover:opacity-20 transition-opacity"></div>
                        <div class="relative z-10 text-center p-8">
                            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 text-shadow-lg font-brand">
                                Çocukların Neşesi
                            </h1>
                            <p class="text-xl md:text-2xl text-shadow">Geleceğin Modası!</p>
                        </div>
                    </section>

                    <section class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <a href="#" class="block sm:col-span-1 bg-gradient-to-r from-pink-400 to-purple-500 p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-white flex flex-col items-center justify-center text-center h-full">
                            <img src="https://img.icons8.com/?size=100&id=12zlRtIjuhaE&format=png&color=000000" alt="Güvenli Ödeme" class="w-16 h-16 mb-4 filter brightness-0 invert" style="filter: brightness(0) invert(1);">
                            <h3 class="font-extrabold text-2xl font-brand">Güvenli Ödeme</h3>
                            <p class="text-sm">Kredi Kartı ile 3D Ödeme</p>
                        </a>
                        <a href="#" class="block sm:col-span-1 bg-gradient-to-r from-pink-400 to-purple-500 p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-white flex flex-col items-center justify-center text-center h-full">
                            <img src="https://img.icons8.com/?size=100&id=hwFnqExjOFhx&format=png&color=000000" alt="Ücretsiz Kargo" class="w-16 h-16 mb-4 filter brightness-0 invert" style="filter: brightness(0) invert(1);">
                            <h3 class="font-extrabold text-2xl font-brand">Ücretsiz Kargo</h3>
                            <p class="text-sm">1000₺ ve Üzeri Alışverişinize!</p>
                        </a>
                        <a href="#" class="block sm:col-span-1 bg-gradient-to-r from-pink-400 to-purple-500 p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-white flex flex-col items-center justify-center text-center h-full">
                            <img src="https://img.icons8.com/?size=100&id=12391&format=png&color=000000" alt="İade & Teslimat" class="w-16 h-16 mb-4 filter brightness-0 invert" style="filter: brightness(0) invert(1);">
                            <h3 class="font-extrabold text-2xl font-brand">İade & Teslimat</h3>
                            <p class="text-sm">14 Gün İçinde</p>
                        </a>
                    </section>

                </main>
            </div>
        </div>

        <section class="bg-white py-16 sm:py-24 border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex justify-center border-b-2 border-gray-200 mb-12">
                    <button 
                        @click="activeTab = 'new'" 
                        :class="activeTab === 'new' ? 'border-pink-500 text-pink-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-8 py-4 text-xl font-bold font-brand border-b-4 transition-colors duration-300">
                        Yeni Gelenler
                    </button>
                    <button 
                        @click="activeTab = 'bestseller'" 
                        :class="activeTab === 'bestseller' ? 'border-pink-500 text-pink-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-8 py-4 text-xl font-bold font-brand border-b-4 transition-colors duration-300">
                        Çok Satanlar
                    </button>
                </div>
                
                <div x-show="activeTab === 'new'" x-transition>
                    @if(isset($latestProducts) && $latestProducts->count() > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 lg:gap-8">
                            @foreach($latestProducts as $product)
                                <x-product-card :product="$product" :show-badge="true" />
                            @endforeach
                        </div>
                        <div class="mt-16 text-center">
                            <a href="{{ route('products.index') }}" 
                               class="inline-block px-12 py-4 bg-pink-600 text-white rounded-lg hover:bg-pink-700 font-bold text-lg transition duration-300">
                                Tüm Yeni Ürünleri Gör
                            </a>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-600 text-lg">Henüz ürün bulunmuyor.</p>
                        </div>
                    @endif
                </div>

                <div x-show="activeTab === 'bestseller'" x-transition style="display: none;">
                    @if(isset($latestProducts) && $latestProducts->count() > 0) 
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 lg:gap-8">
                            @foreach($latestProducts as $product)
                                <x-product-card :product="$product" :show-badge="true" />
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-600 text-lg">Henüz çok satan ürün bulunmuyor.</p>
                        </div>
                    @endif
                </div>

            </div>
        </section>

        {{-- 
            ❗❗ GÜNCELLEME 1: "MARKALAR" BÖLÜMÜNÜN YERİNE ❗❗
            "Haftanın Fırsatları" eklendi.
            Alpine.js ile basit bir geri sayım sayacı içerir.
        --}}
        <section class="bg-purple-50 py-16 sm:py-24 border-t border-purple-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-12 font-brand">
                    <span class="bg-gradient-to-r from-purple-600 to-pink-600 text-transparent bg-clip-text">Haftanın Fırsatları</span>
                </h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    
                    <div class="bg-white rounded-xl shadow-2xl overflow-hidden group transform hover:scale-105 transition-transform duration-500">
                        <a href="/urun/deneme1" class="block">
                            <img src="https://placehold.co/600x600/F3E8FF/A855F7?text=Fırsat+Ürünü" alt="Fırsat Ürünü" class="w-full h-full object-cover">
                        </a>
                    </div>
                    
                    <div 
                        x-data="countdown()" 
                        x-init="start()"
                        class="bg-white rounded-xl shadow-2xl p-8"
                    >
                        <h3 class="text-2xl font-bold text-gray-800">Mavi GAP Tişört</h3>
                        <p class="text-4xl font-extrabold text-pink-600 my-4">
                            199,99 TL
                            <span class="text-2xl text-gray-400 line-through ml-2">255,55 TL</span>
                        </p>
                        <p class="text-gray-600 mb-6">
                            Bu harika fırsatı kaçırmayın! Sadece bu haftaya özel, %20 indirimli.
                        </p>
                        
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="text-center w-20 p-3 bg-pink-100 text-pink-700 rounded-lg shadow-inner">
                                <span class="text-3xl font-bold" x-text="days">00</span>
                                <span class="text-xs font-medium block">Gün</span>
                            </div>
                            <div class="text-center w-20 p-3 bg-pink-100 text-pink-700 rounded-lg shadow-inner">
                                <span class="text-3xl font-bold" x-text="hours">00</span>
                                <span class="text-xs font-medium block">Saat</span>
                            </div>
                            <div class="text-center w-20 p-3 bg-pink-100 text-pink-700 rounded-lg shadow-inner">
                                <span class="text-3xl font-bold" x-text="minutes">00</span>
                                <span class="text-xs font-medium block">Dakika</span>
                            </div>
                            <div class="text-center w-20 p-3 bg-pink-100 text-pink-700 rounded-lg shadow-inner">
                                <span class="text-3xl font-bold" x-text="seconds">00</span>
                                <span class="text-xs font-medium block">Saniye</span>
                            </div>
                        </div>

                        <a href="/urun/deneme1" 
                           class="inline-block w-full text-center px-12 py-4 bg-pink-600 text-white rounded-lg hover:bg-pink-700 font-bold text-lg transition duration-300 transform hover:scale-105">
                            Hemen Al
                        </a>
                    </div>
                </div>

                {{-- Geri sayım sayacı için Alpine.js mantığı --}}
                <script>
                    function countdown() {
                        // Kalan süreyi 3 gün olarak ayarlayalım
                        let targetDate = new Date().getTime() + (3 * 24 * 60 * 60 * 1000);
                        
                        return {
                            days: '00',
                            hours: '00',
                            minutes: '00',
                            seconds: '00',
                            start() {
                                let timer = setInterval(() => {
                                    let now = new Date().getTime();
                                    let distance = targetDate - now;

                                    if (distance < 0) {
                                        clearInterval(timer);
                                        this.days = '00'; this.hours = '00'; this.minutes = '00'; this.seconds = '00';
                                        return;
                                    }

                                    this.days = Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
                                    this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
                                    this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
                                    this.seconds = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0');
                                }, 1000);
                            }
                        }
                    }
                </script>
            </div>
        </section>

        {{-- 
            ❗❗ GÜNCELLEME 2: "BLOG" BÖLÜMÜNÜN YERİNE ❗❗
            "Sizden Gelenler" (Instagram Galerisi) eklendi.
            Bu, siteni daha dolu ve güvenilir gösterir.
        --}}
        <section class="bg-gray-50 py-16 sm:py-24 border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-gray-900 text-center mb-12 font-brand">
                    #KidsCollectt İle Sizden Gelenler
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="#" class="group block aspect-square rounded-xl overflow-hidden shadow-lg relative transform hover:scale-105 transition-transform duration-300">
                        <img src="https://placehold.co/400x400/FBCFE8/9D174D?text=Sizden+Gelen+1" alt="Instagram Post 1" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-opacity duration-300 flex items-center justify-center">
                            <span class="text-white text-lg font-bold opacity-0 group-hover:opacity-100 transition-opacity">@anne_gunlugu</span>
                        </div>
                    </a>
                    <a href="#" class="group block aspect-square rounded-xl overflow-hidden shadow-lg relative transform hover:scale-105 transition-transform duration-300">
                        <img src="https://placehold.co/400x400/DBEAFE/1E40AF?text=Sizden+Gelen+2" alt="Instagram Post 2" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-opacity duration-300 flex items-center justify-center">
                            <span class="text-white text-lg font-bold opacity-0 group-hover:opacity-100 transition-opacity">@bebegimle_gezi</span>
                        </div>
                    </a>
                    <a href="#" class="group block aspect-square rounded-xl overflow-hidden shadow-lg relative transform hover:scale-105 transition-transform duration-300">
                        <img src="https://placehold.co/400x400/D1FAE5/065F46?text=Sizden+Gelen+3" alt="Instagram Post 3" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-opacity duration-300 flex items-center justify-center">
                            <span class="text-white text-lg font-bold opacity-0 group-hover:opacity-100 transition-opacity">@park_zamani</span>
                        </div>
                    </a>
                    <a href="#" class="group block aspect-square rounded-xl overflow-hidden shadow-lg relative transform hover:scale-105 transition-transform duration-300">
                        <img src="https://placehold.co/400x400/FEF9C3/B45309?text=Sizden+Gelen+4" alt="Instagram Post 4" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-opacity duration-300 flex items-center justify-center">
                            <span class="text-white text-lg font-bold opacity-0 group-hover:opacity-100 transition-opacity">@minik_gurme</span>
                        </div>
                    </a>
                </div>

                <div class="mt-12 text-center">
                    <a href="#" {{-- Buraya Instagram linkini koy --}}
                       class="inline-block px-12 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:shadow-xl font-bold text-lg transition duration-300 transform hover:scale-105">
                        Bizi Instagram'da Takip Edin!
                    </a>
                </div>
            </div>
        </section>
        
    </div>
</x-layouts.storefront>