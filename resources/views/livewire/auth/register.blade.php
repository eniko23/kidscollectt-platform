{{-- 
    1. YANLIŞ LAYOUT'U SİLDİK. 
    Ana sitenin layout'unu (storefront) kullanıyoruz.
--}}
<x-layouts.storefront>
    
    {{-- 2. Sayfa Başlığını ekledik (storefront bunu bekler) --}}
    <x-slot:title>
        Kayıt Ol - Kids Collectt
    </x-slot:title>

    {{-- 3. Formu ortalamak ve "çocuk sitesi" görünümü vermek için yeni konteyner --}}
    <div class="py-16 sm:py-24">
        <div class="max-w-md mx-auto bg-white p-8 sm:p-10 rounded-2xl shadow-2xl border-t-4 border-pink-400">
            
            {{-- O "siyah" <x-auth-header> yerine kendi başlığımızı koyduk --}}
            <div class="flex flex-col items-center justify-center gap-4 mb-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <span class="text-5xl">🎈</span>
                </a>
                <h2 class="text-3xl font-bold text-gray-900">
                    Hesap Oluşturun
                </h2>
                <p class="text-sm text-gray-600">
                    Detayları girerek aramıza katılın.
                </p>
            </div>

            <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
                @csrf
                
                {{-- 4. Senin FLUX component'lerini koruduk, sadece TÜRKÇELEŞTİRDİK --}}
                <flux:input
                    name="name"
                    :label="__('Adınız Soyadınız')"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    :placeholder="__('Adınız Soyadınız')"
                />

                <flux:input
                    name="email"
                    :label="__('E-posta Adresiniz')"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="email@example.com"
                />

                <flux:input
                    name="password"
                    :label="__('Şifre')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('••••••••')"
                    viewable
                />

                <flux:input
                    name="password_confirmation"
                    :label="__('Şifre (Tekrar)')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('••••••••')"
                    viewable
                />

                <div class="flex items-center justify-end">
                    {{-- 5. BUTONU SİTENİN RENKLERİNE BOYADIK --}}
                    <flux:button 
                        type="submit" 
                        variant="primary" 
                        class="w-full !text-lg !font-bold !text-white !border-transparent
                               bg-gradient-to-r from-pink-500 via-purple-500 to-pink-500 
                               hover:from-pink-600 hover:via-purple-600 hover:to-pink-600 
                               transition-all duration-300 transform hover:scale-105" 
                        data-test="register-user-button">
                        {{ __('Hesap Oluştur') }}
                    </flux:button>
                </div>
            </form>

            <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-gray-600 mt-6">
                <span>{{ __('Zaten hesabın var mı?') }}</span>
                {{-- 6. LİNKİ RENKLENDİRDİK --}}
                <flux:link 
                    :href="route('login')" 
                    wire:navigate 
                    class="!text-pink-600 hover:!text-purple-600 !font-bold">
                    {{ __('Giriş Yap') }}
                </flux:link>
            </div>
        </div>
    </div>
</x-layouts.storefront>