<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia; // <-- EKLENDİ
use Spatie\MediaLibrary\InteractsWithMedia; // <-- EKLENDİ
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\VatCalculator;

class ProductVariant extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    /**
     * Toplu atamaya izin verilen alanların listesi.
     */
    protected $fillable = [
        'product_id',
        'size',
        'color_name',
        'color_code',
        'color_name_2',
        'color_code_2',
        'min_quantity',
        'price',
        'sale_price', // <-- YENİ EKLENDİ (İndirimli Fiyat)
        'bayii_price', // <-- EKSİKTİ, EKLENDİ
        'stock',
        'sku',
        'barcode',
        'original_image_url',
    ];

    /**
     * Alanların tiplerini belirleme.
     */
    protected $casts = [
        'price' => 'integer',
        'sale_price' => 'integer', // <-- YENİ EKLENDİ
        'bayii_price' => 'integer', // <-- EKSİKTİ, EKLENDİ
        'stock' => 'integer',
    ];

    /**
     * Bu varyantın ait olduğu ana "Ürünü" getirir.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // --- YENİ YARDIMCI METODLAR (KDV ve İNDİRİM İÇİN) ---

    /**
     * KDV Dahil Normal Fiyat (Kuruş)
     */
    public function getGrossPriceAttribute()
    {
        return VatCalculator::calculate($this->price, $this->product?->vat_rate ?? 0);
    }

    /**
     * KDV Dahil İndirimli Fiyat (Kuruş)
     */
    public function getGrossSalePriceAttribute()
    {
        if ($this->sale_price) {
            return VatCalculator::calculate($this->sale_price, $this->product?->vat_rate ?? 0);
        }
        return null;
    }

    // --- MEVCUT YARDIMCI METODLAR (İNDİRİM İÇİN) ---

    /**
     * İndirimli fiyatın geçerli olup olmadığını kontrol eder.
     * sale_price varsa ve normal fiyattan (price) düşükse indirim geçerlidir.
     */
    public function hasDiscount(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    /**
     * İndirim oranını yüzde olarak hesaplar.
     * @return int|null
     */
    public function getDiscountPercent(): ?int
    {
        if (!$this->hasDiscount()) {
            return null;
        }

        // Sıfıra bölme hatasını engelle
        if ($this->price <= 0) {
            return 0;
        }

        $discountAmount = $this->price - $this->sale_price;
        $discountPercent = ($discountAmount / $this->price) * 100;

        return (int)round($discountPercent); // Yuvarla ve tam sayıya çevir
    }

    /**
     * Gösterilecek olan son fiyatı (İndirimli veya normal) TL formatında döndürür.
     * Örn: "149,99 TL"
     */
    public function getFormattedPrice(): string
    {
        $priceInCents = $this->hasDiscount() ? $this->sale_price : $this->price;
        return number_format($priceInCents / 100, 2, ',', '.') . ' TL';
    }

    /**
     * Eğer indirim varsa, üstü çizili normal fiyatı TL formatında döndürür.
     * Örn: "199,99 TL"
     */
    public function getFormattedOldPrice(): ?string
    {
        if (!$this->hasDiscount()) {
            return null;
        }
        
        return number_format($this->price / 100, 2, ',', '.') . ' TL';
    }
}