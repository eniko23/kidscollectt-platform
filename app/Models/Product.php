<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia; // <-- EKLENDİ
use Spatie\MediaLibrary\InteractsWithMedia; // <-- EKLENDİ
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
}