{{-- 1. Yeni 'beeshop' şablonumuzu kullandığımızı belirtiyoruz --}}
<x-layouts.beeshop>

    {{-- 2. Bu sayfanın 'body' etiketine özel bir class eklemesi için (opsiyonel) --}}
    <x-slot:title>Ana Sayfa | Kids Collectt</x-slot:title>
    <x-slot:bodyClass>home-three</x-slot:bodyClass>

    {{-- 3. 'content' (içerik) bölümünü dolduruyoruz --}}
    @section('content')
    
    <section>
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3">
                    <div class="left-section">
                        <div class="home-two-menu">
                            {{-- TODO: Burası Mega Menünün 2. kısmı. Bunu da dinamik yapacağız. --}}
                            <ul class="nav">
                                <li class="mega-menu-one"><a href="#"><span><img src="{{ asset('img/menu-i/icon_bee.png') }}" alt="menu-icon" /></span>Kids Beds</a><i class="fa fa-angle-right"></i>
                                    <div class="mega-menu">...</div>
                                </li> 
                                <li class="mega-menu-two"><a href="#"><span><img src="{{ asset('img/menu-i/33-0_thumb.jpg.png') }}" alt="menu-icon" /></span>Dressers</a><i class="fa fa-angle-right"></i>
                                    <div class="mega-menu">...</div>
                                </li>
                                {{-- ... (Diğer statik menü elemanları) ... --}}
                            </ul>
                        </div>						
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9">
                    <div class="right-section"> 
                        <div class="slider-area bg-gray">
                            <div class="bend niceties preview-2">
                                <div id="ensign-nivoslider" class="slides">	
                                    {{-- TODO: Bu slider'ı dinamik hale getireceğiz --}}
                                    <img src="{{ asset('img/slider/slider-1/3.jpg') }}" alt="" title="#slider-direction-1"  />
                                    <img src="{{ asset('img/slider/slider-1/4.jpg') }}" alt="" title="#slider-direction-2"  />
                                </div>
                                <div id="slider-direction-1" class="t-cn slider-direction">
                                    <div class="slider-content t-lfr s-tb slider-1">
                                        <div class="title-container s-tb-c">
                                            <h1 class="title1">Bee Clothing for boys</h1>
                                            <h3 class="title2" >Active baby all day comfort</h3>
                                            <h4 class="title3" >Reliability good</h4>
                                            <a href="{{ route('products.index') }}" class="shop-button">Shop now</a>
                                        </div>
                                    </div>	
                                </div>
                                <div id="slider-direction-2" class="slider-direction">
                                    <div class="slider-content t-lfr s-tb slider-2">
                                        <div class="title-container s-tb-c title-compress">
                                            <h1 class="title1">Designer dolls much Bee</h1>
                                            <h3 class="title2" >Active baby all day comfort</h3>
                                            <h4 class="title3" >Reliability good</h4>
                                            <a href="{{ route('products.index') }}" class="shop-button">Shop now</a>
                                        </div>
                                    </div>	
                                </div>
                            </div>
                        </div>							
                        </div>
                </div>				
            </div>
        </div> 
    </section>
    
    {{-- ... (Promotion Area'nın tüm statik HTML'i buraya gelecek) ... --}}
    <section class="promotion-area">
        {{-- ... (tüm img src'lerini asset() ile güncellemeyi unutma) ... --}}
    </section> 

    <section class="product-area">
        {{-- TODO: Burası "Yeni Gelenler" ve "Çok Satanlar" için dinamik Livewire bileşenlerimizin geleceği yer --}}
        <div class="container">
            {{-- ... (Statik New Arrival / Bestseller HTML'i buraya gelecek) ... --}}
        </div>
    </section> 
    
    <section class="featured-product-area">
        {{-- TODO: Burası "Öne Çıkan Ürünler" için dinamik Livewire bileşenimizin geleceği yer --}}
        <div class="container">
            {{-- ... (Statik Featured Products HTML'i buraya gelecek) ... --}}
        </div> 
    </section>
    
    @endsection

</x-layouts.beeshop>