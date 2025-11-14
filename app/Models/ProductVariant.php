<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia; // <-- EKLENDİ
use Spatie\MediaLibrary\InteractsWithMedia; // <-- EKLENDİ
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'min_quantity',
        'price',
        'stock',
        'sku',
    ];

    /**
     * Alanların tiplerini belirleme.
     * 'price' (fiyat) veritabanında 19999 (kuruş) olarak saklanır,
     * ama kodda 199.99 (TL) olarak davranır.
     */
    protected $casts = [
        'price' => 'integer', // Veritabanında integer (kuruş) olduğunu belirtiyoruz
        'stock' => 'integer',
    ];

    /**
     * Bu varyantın ait olduğu ana "Ürünü" getirir.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}