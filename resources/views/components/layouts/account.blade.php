{{-- BU DOSYA: resources/views/components/layouts/account.blade.php --}}
{{-- Bu, "Hesabım" bölümünün ana iskeletidir. --}}

<x-layouts.storefront>
    {{-- $title değişkenini (eğer varsa) alır, yoksa 'Hesabım' yazar --}}
    <x-slot:title>
        {{ $title ?? 'Hesabım' }} - Kids Collectt
    </x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            <aside class="md:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">
                        Hesabım
                    </h2>
                    <nav class="space-y-2">
                        
                        {{-- Rota kontrolleri, hangi sayfadaysan o linki pembe yapar --}}
                        
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors duration-200
                                {{ request()->routeIs('dashboard') 
                                    ? 'bg-pink-100 text-pink-700' 
                                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            <span>Hesap Özeti</span>
                        </a>

                        {{-- Bu rotayı Adım 3'te ekleyeceğiz --}}
                        <a href="{{ route('account.orders') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors duration-200
                                {{ request()->routeIs('account.orders') 
                                    ? 'bg-pink-100 text-pink-700' 
                                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            <span>Siparişlerim</span>
                        </a>

                        <a href="{{ route('profile.edit') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors duration-200
                                {{ request()->routeIs('profile.edit') 
                                    ? 'bg-pink-100 text-pink-700' 
                                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>Profil Ayarları</span>
                        </a>
                        
                        <a href="{{ route('user-password.edit') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors duration-200
                                {{ request()->routeIs('user-password.edit') 
                                    ? 'bg-pink-100 text-pink-700' 
                                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <span>Şifre Değiştir</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="w-full pt-2 border-t border-gray-200">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span>Çıkış Yap</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </aside>

            <main class="md:col-span-3">
                <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg border border-gray-200 min-h-[400px]">
                    {{-- Tıkladığın sayfanın içeriği ($slot) buraya yüklenecek --}}
                    {{ $slot }}
                </div>
            </main>

        </div>
    </div>
</x-layouts.storefront>