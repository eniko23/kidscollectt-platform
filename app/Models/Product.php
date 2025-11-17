<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;
    /**
     * Toplu atamaya izin verilen alanların listesi.
     * Bu, MassAssignmentException hatasını önler.
     */
    protected $fillable = [
    'name',
    'slug',
    'description',
    'category_id',
    'is_active',
    // --- YENİ ALANLAR ---
    'vat_rate',
    'should_track_stock',
    'meta_keywords',
    'meta_description',
    // ------------------
    ];

    /**
     * boolean (true/false) olarak davranması gereken alanlar.
     * 'is_active' alanını 1/0 yerine true/false olarak yönetir.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'should_track_stock' => 'boolean',
    ];


    /**
     * Bu ürünün ait olduğu "Kategoriyi" getirir.
     */
    public function category(): BelongsTo
    {
        // 'category_id' sütunu üzerinden 'categories' tablosundaki 'id'ye bağlanır.
        return $this->belongsTo(Category::class, 'category_id');
    }
    // ... category() fonksiyonunun bittiği yer ...

    /**
    * Bu ürüne ait tüm "Varyantları" (beden, renk vb.) getirir.
    */
    public function variants(): HasMany
    {
        // 'product_variants' tablosundaki 'product_id' sütunu üzerinden bağlanır.
        return $this->hasMany(ProductVariant::class, 'product_id');
    }


    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(300)  // 300px genişlik
              ->height(300) // 300px yükseklik
              ->sharpen(10) // Biraz keskinleştir
              ->nonQueued(); // Kuyruğa atmadan hemen oluştur
    }

    // ===================================================================
    // === YENİ FİYAT HESAPLAMA FONKSİYONLARI (BURADAN İTİBAREN EKLENDİ) ===
    // ===================================================================

    /**
     * 1. YARDIMCI: Ürünün en düşük NORMAL fiyatını (kuruş) bulur.
     * Blade içinde $product->min_price olarak erişilebilir.
     */
    public function getMinPriceAttribute()
    {
        // variants() ilişkisinden 'price' kolonunun minimumunu alır.
        return $this->variants()->min('price');
    }

    /**
     * 2. YARDIMCI: Ürünün en düşük İNDİRİMLİ fiyatını (kuruş) bulur.
     * Blade içinde $product->min_sale_price olarak erişilebilir.
     */
    public function getMinSalePriceAttribute()
    {
        // Sadece null olmayan ve 0'dan büyük olan indirimli fiyatları dikkate alır.
        return $this->variants()
                    ->whereNotNull('sale_price')
                    ->where('sale_price', '>', 0)
                    ->min('sale_price');
    }

    /**
     * 3. VİTRİN: Gösterilecek GÜNCEL FİYAT (TL Cinsinden)
     * Blade'de $product->display_price olarak kullanılır.
     *
     * @return string (Formatlanmış TL Fiyatı)
     */
    public function getDisplayPriceAttribute()
    {
        // Yukarıdaki yardımcı fonksiyonları çağırır
        $minSalePrice = $this->getMinSalePriceAttribute();
        $minPrice = $this->getMinPriceAttribute();

        $displayPrice = $minSalePrice ?? $minPrice; // İndirimli fiyat varsa onu, yoksa normal fiyatı al

        // Eğer (hatalı veri girişi vb.) indirimli fiyat normal fiyattan yüksekse,
        // yine de düşük olan normal fiyatı göster.
        if ($minSalePrice && $minPrice && $minSalePrice >= $minPrice) {
            $displayPrice = $minPrice;
        }

        if ($displayPrice === null) {
            return "Fiyat Yok"; // Hiç varyant/fiyat girilmemişse
        }

        // Kuruşu TL'ye çevir (örn: 19999 -> 199,99)
        // number_format($number, $decimals, $decimal_separator, $thousands_separator)
        return number_format($displayPrice / 100, 2, ',', '.');
    }

    /**
     * 4. VİTRİN: Gösterilecek ESKİ (ÜSTÜ ÇİZİLİ) FİYAT (TL Cinsinden)
     * Blade'de $product->display_old_price olarak kullanılır.
     *
     * @return string|null (Formatlanmış TL Fiyatı veya indirim yoksa null)
     */
    public function getDisplayOldPriceAttribute()
    {
        // Yukarıdaki yardımcı fonksiyonları çağırır
        $minSalePrice = $this->getMinSalePriceAttribute();
        $minPrice = $this->getMinPriceAttribute();

        // Sadece gerçek bir indirim varsa (indirimli fiyat normalden düşükse)
        // eski fiyatı (yani normal fiyatı) döndür.
        if ($minSalePrice && $minPrice && $minSalePrice < $minPrice) {
            
            // Kuruşu TL'ye çevir
            return number_format($minPrice / 100, 2, ',', '.');
        }

        // İndirim yoksa null döndür
        return null;
    }

} // <-- Class Product biter