<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use HasFactory;
    
    // updateOrCreate için $fillable zorunlu
    protected $fillable = ['key', 'value'];

    /**
     * 'featured_product_id' ayarı için
     * 'Product' modeline bir ilişki.
     */
    public function featuredProduct(): BelongsTo
    {
        // 'value' sütununun 'products' tablosundaki 'id'ye eşit olduğunu varsay
        return $this->belongsTo(Product::class, 'value');
    }
}