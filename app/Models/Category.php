<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- BUNU EKLEYİN
use Illuminate\Database\Eloquent\Relations\HasMany;   // <-- BUNU EKLEYİN

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    // --- BU İKİ FONKSİYONU EKLEYİN ---

    /**
     * Bu kategorinin (eğer varsa) ait olduğu "Ana Kategoriyi" getirir.
     */
    public function parent(): BelongsTo
    {
        // 'parent_id' sütunu üzerinden 'categories' tablosundaki 'id'ye bağlanır.
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Bu kategorinin (eğer varsa) sahip olduğu "Alt Kategorileri" getirir.
     */
    public function children(): HasMany
    {
        // 'categories' tablosundaki 'parent_id' sütunu üzerinden bağlanır.
        return $this->hasMany(Category::class, 'parent_id');
    }

    // ... (parent() ve children() fonksiyonları) ...

    /**
     * Bu kategoriye ait tüm "Ürünleri" (Products) getirir.
     */
    public function products(): HasMany
    {
        // 'products' tablosundaki 'category_id' sütunu üzerinden bağlanır.
        return $this->hasMany(Product::class, 'category_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}