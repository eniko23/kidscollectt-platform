{{-- 
    BU DOSYA: resources/views/dashboard.blade.php
    Artık bu dosya SADECE "Hesap Özeti" içeriğini tutuyor.
    İskeleti (menü vs.) Adım 1'de yaptığımız <x-layouts.account>'tan alıyor.
--}}
<x-layouts.account>
    {{-- Sayfa Başlığı --}}
    <x-slot:title>
        Hesap Özeti
    </x-slot:title>
    
    {{-- Sayfa İçeriği --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-4">
            Hesap Özeti
        </h2>
        
        {{-- "Hoş geldin, !" hatasını burada düzelttim --}}
        <p class="text-gray-700">
            Hoş geldin, <span class="font-bold">{{ request()->user()->name }}</span>!
        </p>
        <p class="mt-4">
            Bu panelden siparişlerini takip edebilir ve hesap bilgilerini güncelleyebilirsin.
        </p>
    </div>
</x-layouts.account>