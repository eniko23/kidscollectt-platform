<x-layouts.storefront>
    <x-slot:title>
        Ana Sayfa - Kids Collectt
    </x-slot:title>

    <div x-data="{ activeTab: 'new' }" class="bg-gray-100">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <aside class="hidden lg:block lg:col-span-1 p-6 bg-white rounded-xl shadow-lg h-fit border border-gray-200 sticky top-28">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b-2 border-pink-300">
                        Kategoriler
                    </h2>
                    <nav>
                        <ul class="space-y-3">
                            <li><a href="{{ route('products.index') }}" class="flex items-center group font-medium text-gray-700 hover:text-pink-600 transition duration-200">
                                <span class="w-8 h-8 mr-3 rounded-full bg-gray-100 flex items-center justify-center text-lg group-hover:bg-gray-200 transition-all">🛍️</span>
                                Tüm Ürünler
                            </a></li>
                            <li><a href="/urunler" class="flex items-center group font-medium text-gray-700 hover:text-pink-600 transition duration-200">
                                <span class="w-8 h-8 mr-3 rounded-full bg-orange-100 flex items-center justify-center text-lg group-hover:bg-orange-200 transition-all">🔥</span>
                                En Çok Satanlar
                            </a></li>
                            <li><a href="/kategori/erkek-cocuk-giyim" class="flex items-center group font-medium text-gray-700 hover:text-blue-600 transition duration-200">
                                <span class="w-8 h-8 mr-3 rounded-full bg-blue-100 flex items-center justify-center text-lg group-hover:bg-blue-200 transition-all">👦</span>
                                Erkek Çocuk
                            </a></li>
                            <li><a href="/kategori/kız-çocuk-giyim" class="flex items-center group font-medium text-gray-700 hover:text-pink-600 transition duration-200">
                                <span class="w-8 h-8 mr-3 rounded-full bg-pink-100 flex items-center justify-center text-lg group-hover:bg-pink-200 transition-all">👧</span>
                                Kız Çocuk
                            </a></li>
                            <li><a href="/kategori/yetişkin-giyim" class="flex items-center group font-medium text-gray-700 hover:text-purple-600 transition duration-200">
                                <span class="w-8 h-8 mr-3 rounded-full bg-purple-100 flex items-center justify-center text-lg group-hover:bg-purple-200 transition-all">👩</span>
                                Yetişkin
                            </a></li>
                            <li><a href="/urunler" class="flex items-center group font-medium text-red-700 hover:text-red-600 transition duration-200">
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
                        
                        <div class="relative z-10 text-center p-8">
                            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 text-shadow-lg font-brand">
                                Çocukların Neşesi
                            </h1>
                            <p class="text-xl md:text-2xl text-shadow">Geleceğin Modası!</p>
                        </div>
                    </section>

                    <section class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <a href="#" class="block sm:col-span-1 bg-gradient-to-r from-pink-400 to-purple-500 p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-white flex flex-col items-center justify-center text-center h-full">
                            <img src="https://img.icons8.com/?size=100&id=12zlRtIjuhaE&format=png&color=000000" alt="Güvenli Ödeme" class="w-16 h-16 mb-4 filter brightness-0 invert">
                            <h3 class="font-extrabold text-2xl font-brand">Güvenli Ödeme</h3>
                            <p class="text-sm">Kredi Kartı ile 3D Ödeme</p>
                        </a>
                        <a href="#" class="block sm:col-span-1 bg-gradient-to-r from-pink-400 to-purple-500 p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-white flex flex-col items-center justify-center text-center h-full">
                            <img src="https://img.icons8.com/?size=100&id=hwFnqExjOFhx&format=png&color=000000" alt="Ücretsiz Kargo" class="w-16 h-16 mb-4 filter brightness-0 invert">
                            <h3 class="font-extrabold text-2xl font-brand">Ücretsiz Kargo</h3>
                            <p class="text-sm">1000₺ ve Üzeri Alışverişinize!</p>
                        </a>
                        <a href="#" class="block sm:col-span-1 bg-gradient-to-r from-pink-400 to-purple-500 p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 text-white flex flex-col items-center justify-center text-center h-full">
                            <img src="https://img.icons8.com/?size=100&id=12391&format=png&color=000000" alt="İade & Teslimat" class="w-16 h-16 mb-4 filter brightness-0 invert">
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

        {{-- Haftanın Fırsatları --}}
@if($featuredProduct && $salePrice && $expiresAt)
<section class="bg-purple-50 py-16 sm:py-24 border-t border-purple-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-12 font-brand">
            <span class="bg-gradient-to-r from-purple-600 to-pink-600 text-transparent bg-clip-text">Haftanın Fırsatları</span>
        </h2>
        
        <div class="flex flex-col lg:flex-row gap-8 items-stretch">
            
            {{-- Resim --}}
            <div class="flex-1 bg-white rounded-xl shadow-2xl overflow-hidden group flex justify-center items-center">
                <a href="{{ route('products.show', $featuredProduct) }}" class="block w-full h-full">
                    <img src="{{ $featuredProduct->getFirstMedia('product-images')?->getUrl() ?? 'https://placehold.co/600x600/F3E8FF/A855F7?text=Resim+Yok' }}" 
                         alt="{{ $featuredProduct->name }}" 
                         class="w-full h-full object-cover aspect-square">
                </a>
            </div>
            
            {{-- Sayaç ve Bilgi --}}
            <div 
                x-data="countdown('{{ $expiresAt }}')" 
                x-init="start()"
                class="flex-1 bg-white rounded-xl shadow-2xl p-8 flex flex-col justify-between"
            >
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $featuredProduct->name }}</h3>
                    
                    <p class="text-4xl font-extrabold text-pink-600 my-4">
                        {{ number_format($salePrice / 100, 2, ',', '.') }} TL
                        <span class="text-2xl text-gray-400 line-through ml-2">
                            {{ number_format($featuredProduct->variants->min('price') / 100, 2, ',', '.') }} TL
                        </span>
                    </p>
                    
                    <p class="text-gray-600 mb-6">
                        Bu harika fırsatı kaçırmayın! Sadece bu haftaya özel.
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
                </div>

                <a href="{{ route('products.show', $featuredProduct) }}" 
                   class="mt-auto inline-block w-full text-center px-12 py-4 bg-pink-600 text-white rounded-lg hover:bg-pink-700 font-bold text-lg transition duration-300 transform hover:scale-105">
                    Hemen Al
                </a>
            </div>
        </div>

        <script>
            function countdown(targetDate) {
                let targetTime = new Date(targetDate).getTime();
                
                return {
                    days: '00',
                    hours: '00',
                    minutes: '00',
                    seconds: '00',
                    start() {
                        let timer = setInterval(() => {
                            let now = new Date().getTime();
                            let distance = targetTime - now;

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
@endif


        {{-- İndirimdeki Ürünler (Carousel) --}}
        @if(isset($discountedProducts) && $discountedProducts->count() > 0)
        <section class="bg-red-50 py-16 sm:py-24 border-t border-red-100 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-extrabold font-brand flex items-center gap-3">
    <span class="text-4xl">🏷️</span>
    <span class="bg-gradient-to-r from-orange-500 via-yellow-400 to-amber-500
                 text-transparent bg-clip-text animate-pulse">
        Süper İndirimler
    </span>
</h2>

                    <a href="/urunler" class="hidden sm:block text-pink-600 font-bold hover:text-pink-700 hover:underline">Tümünü Gör →</a>
                </div>

                <div 
                    x-data="{ 
                        scrollLeft: 0,
                        container: null,
                        maxScroll: 0,
                        init() {
                            this.container = this.$refs.scrollContainer;
                            this.updateMaxScroll();
                            // Otomatik kaydırma
                            setInterval(() => {
                                if (this.container) {
                                    if (this.container.scrollLeft >= (this.container.scrollWidth - this.container.clientWidth - 10)) {
                                        this.container.scrollTo({ left: 0, behavior: 'smooth' });
                                    } else {
                                        this.container.scrollBy({ left: 300, behavior: 'smooth' });
                                    }
                                }
                            }, 5000);
                        },
                        updateMaxScroll() {
                            if(this.container) this.maxScroll = this.container.scrollWidth - this.container.clientWidth;
                        },
                        scroll(direction) {
                            const amount = direction === 'left' ? -300 : 300;
                            this.container.scrollBy({ left: amount, behavior: 'smooth' });
                        }
                    }" 
                    class="relative group"
                >
                    {{-- Sol Ok --}}
                    <button 
                        @click="scroll('left')"
                        class="absolute left-0 top-1/2 -translate-y-1/2 -ml-4 z-10 bg-white text-gray-800 p-3 rounded-full shadow-lg hover:bg-pink-50 hover:text-pink-600 transition-all focus:outline-none opacity-0 group-hover:opacity-100 hidden sm:block"
                        aria-label="Sola Kaydır"
                    >
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>

                    {{-- Kaydırılabilir Alan --}}
                    <div 
                        x-ref="scrollContainer"
                        @scroll.debounce="updateMaxScroll"
                        class="flex gap-6 overflow-x-auto pb-8 snap-x snap-mandatory no-scrollbar"
                        style="scroll-behavior: smooth; -webkit-overflow-scrolling: touch;"
                    >
                        @foreach($discountedProducts as $product)
                            <div class="flex-none w-64 snap-start">
                                <x-product-card :product="$product" :show-badge="true" />
                            </div>
                        @endforeach
                    </div>

                    {{-- Sağ Ok --}}
                    <button 
                        @click="scroll('right')"
                        class="absolute right-0 top-1/2 -translate-y-1/2 -mr-4 z-10 bg-white text-gray-800 p-3 rounded-full shadow-lg hover:bg-pink-50 hover:text-pink-600 transition-all focus:outline-none opacity-0 group-hover:opacity-100 hidden sm:block"
                        aria-label="Sağa Kaydır"
                    >
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>
        </section>
        @endif

        {{-- Ekstra İçerik Alanı: Kategoriler Vitrini --}}
        <!-- <section class="py-16 sm:py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-12 font-brand">Keşfetmeye Başla</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    {{-- Erkek Çocuk --}}
                    <a href="/kategori/erkek-giyim" class="relative rounded-2xl overflow-hidden group h-64 shadow-md">
                        <img src="https://images.unsplash.com/photo-1519457431-44ccd64a579b?q=80&w=800&auto=format&fit=crop" alt="Erkek Çocuk" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex flex-col justify-end p-6">
                            <span class="text-white font-bold text-xl mb-1">Erkek Çocuk</span>
                            <span class="text-gray-200 text-sm group-hover:translate-x-2 transition-transform duration-300">İncele →</span>
                        </div>
                    </a>
                    
                    {{-- Kız Çocuk --}}
                    <a href="/kategori/kiz-cocuk" class="relative rounded-2xl overflow-hidden group h-64 shadow-md">
                        <img src="https://images.unsplash.com/photo-1513159446162-54eb8bdaa79b?q=80&w=800&auto=format&fit=crop" alt="Kız Çocuk" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex flex-col justify-end p-6">
                            <span class="text-white font-bold text-xl mb-1">Kız Çocuk</span>
                            <span class="text-gray-200 text-sm group-hover:translate-x-2 transition-transform duration-300">İncele →</span>
                        </div>
                    </a>

                    {{-- Bebek Giyim --}}
                    <a href="/kategori/bebek-giyim" class="relative rounded-2xl overflow-hidden group h-64 shadow-md col-span-2 md:col-span-1">
                        <img src="https://images.unsplash.com/photo-1522771930-78848d9293e8?q=80&w=800&auto=format&fit=crop" alt="Bebek Giyim" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex flex-col justify-end p-6">
                            <span class="text-white font-bold text-xl mb-1">Bebek Giyim</span>
                            <span class="text-gray-200 text-sm group-hover:translate-x-2 transition-transform duration-300">İncele →</span>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        {{-- Bülten Aboneliği (Görsel) --}}
        <section class="bg-gradient-to-r from-pink-500 to-purple-600 py-16">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold text-white mb-4 font-brand">Fırsatlardan İlk Sen Haberdar Ol!</h2>
                <p class="text-pink-100 mb-8 text-lg">E-posta listemize katıl, yeni sezon ürünleri ve özel indirimleri kaçırma.</p>
                <form class="flex flex-col sm:flex-row gap-4 justify-center" onsubmit="event.preventDefault(); alert('Teşekkürler! (Demo)');">
                    <input type="email" placeholder="E-posta adresin..." class="px-6 py-4 rounded-full text-gray-800 focus:outline-none focus:ring-4 focus:ring-pink-300 w-full sm:w-96" required>
                    <button type="submit" class="px-8 py-4 bg-yellow-400 text-purple-900 font-bold rounded-full hover:bg-yellow-300 transition-colors shadow-lg">
                        Abone Ol
                    </button>
                </form>
            </div>
        </section> --!>
        
    </div>
</x-layouts.storefront>
