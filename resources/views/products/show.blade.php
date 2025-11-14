{{-- Bu sayfa, ana şablonumuz olan 'storefront'u kullanacak --}}
<x-layouts.storefront>

    <x-slot:title>
        {{ $product->name }} - Kids Collectt
    </x-slot:title>

    {{-- 
        ANA İÇERİK
        Tüm o "statik" HTML yerine, artık 'ProductDetail' adındaki
        interaktif Livewire bileşenini çağırıyoruz.

        Kontrolcüden gelen '$product' değişkenini de
        bu bileşene 'product' adında bir parametre olarak iletiyoruz.
    --}}
    @livewire('product-detail', ['product' => $product])

</x-layouts.storefront>