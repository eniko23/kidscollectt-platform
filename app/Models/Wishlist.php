<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    /**
     * Toplu atamaya izin verilen alanların listesi.
     */
    protected $fillable = [
        'user_id',
        'product_id',
    ];

    /**
     * Bu favori kaydının ait olduğu "Müşteriyi" (User) getirir.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Bu favori kaydının ait olduğu "Ürünü" (Product) getirir.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}