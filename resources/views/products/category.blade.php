{{-- 
    Bu senin kategori sayfan.
    Artık "product-card" component'ini burada kullanıyor.
--}}
<x-layouts.storefront>
    
    <x-slot:title>
        {{ $category->name }} - Kids Collectt
    </x-slot:title>

    {{-- Ana sayfanla uyumlu olması için başlık bölümü eklendi --}}
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-2">
             {{ $category->name }}
        </h1>
        <p class="text-lg text-gray-600 mb-8">
            {{ $category->description ?? 'Bu kategorideki en yeni ve en güzel ürünlerimiz.' }}
        </p>
        {{-- Başlık bölümü bitti --}}


        @if(isset($products) && $products->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 lg:gap-8">
                
                {{-- 
                    ❗❗ DEĞİŞİKLİK BURADA ❗❗
                    Component'i :show-badge="true" OLMADAN çağırıyoruz.
                    Böylece varsayılan (false) değeri alacak ve etiket GÖSTERİLMEYECEK.
                --}}
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
                
            </div>

            {{-- Sayfalama (Pagination) linkleri --}}
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @else
            {{-- "Ürün yok" mesajı --}}
            <div class="col-span-full text-center py-12 bg-white rounded-xl shadow border">
                <h2 class="text-xl text-gray-600">Bu kategoride henüz ürün bulunmuyor.</h2>
            </div>
        @endif
    </div>

</x-layouts.storefront>