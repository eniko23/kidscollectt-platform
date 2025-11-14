{{-- 1. YANLIŞ LAYOUT'U SİLDİK --}}
<x-layouts.storefront>

    {{-- 2. Sayfa Başlığını ekledik --}}
    <x-slot:title>
        Giriş Yap - Kids Collectt
    </x-slot:title>

    {{-- 3. Formu ortalamak için yeni konteyner --}}
    <div class="py-16 sm:py-24">
        <div class="max-w-md mx-auto bg-white p-8 sm:p-10 rounded-2xl shadow-2xl border-t-4 border-purple-400">
            
            {{-- 4. Kendi başlığımızı koyduk --}}
            <div class="flex flex-col items-center justify-center gap-4 mb-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <span class="text-5xl">🎈</span>
                </a>
                <h2 class="text-3xl font-bold text-gray-900">
                    Tekrar Hoş Geldin!
                </h2>
                <p class="text-sm text-gray-600">
                    Giriş yapmak için bilgilerinizi girin.
                </p>
            </div>

            <x-auth-session-status class="text-center mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
                @csrf

                {{-- 5. FLUX component'lerini koruduk ve TÜRKÇELEŞTİRDİK --}}
                <flux:input
                    name="email"
                    :label="__('E-posta Adresiniz')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="email@example.com"
                />

                <div class="relative">
                    <flux:input
                        name="password"
                        :label="__('Şifreniz')"
                        type="password"
                        required
                        autocomplete="current-password"
                        :placeholder="__('••••••••')"
                        viewable
                    />

                    @if (Route::has('password.request'))
                        {{-- 6. LİNKİ RENKLENDİRDİK --}}
                        <flux:link 
                            class="absolute top-0 text-sm end-0 !text-pink-600 hover:!text-purple-600" 
                            :href="route('password.request')" 
                            wire:navigate>
                            {{ __('Şifreni mi unuttun?') }}
                        </flux:link>
                    @endif
                </div>

                <flux:checkbox name="remember" :label="__('Beni Hatırla')" :checked="old('remember')" />

                <div class="flex items-center justify-end">
                    {{-- 7. BUTONU RENKLENDİRDİK --}}
                    <flux:button 
                        type="submit" 
                        variant="primary" 
                        class="w-full !text-lg !font-bold !text-white !border-transparent
                               bg-gradient-to-r from-purple-500 via-pink-500 to-purple-500 
                               hover:from-purple-600 hover:via-pink-600 hover:to-purple-600 
                               transition-all duration-300 transform hover:scale-105" 
                        data-test="login-button">
                        {{ __('Giriş Yap') }}
                    </flux:button>
                </div>
            </form>

            @if (Route::has('register'))
                <div class="space-x-1 text-sm text-center text-gray-600 mt-6">
                    <span>{{ __('Hesabın yok mu?') }}</span>
                    {{-- 8. LİNKİ RENKLENDİRDİK --}}
                    <flux:link 
                        :href="route('register')" 
                        wire:navigate 
                        class="!text-pink-600 hover:!text-purple-600 !font-bold">
                        {{ __('Hemen Kayıt Ol') }}
                    </flux:link>
                </div>
            @endif
        </div>
    </div>
</x-layouts.storefront>