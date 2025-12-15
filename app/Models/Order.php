<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * Toplu atamaya izin verilen alanlar (Mass Assignment).
     */
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'shipping_address',
        'payment_method',
        'payment_transaction_id',
    ];

    /**
     * Bu siparişin hangi "Kullanıcıya" ait olduğunu getirir.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Bu siparişin içindeki tüm "Sipariş Kalemlerini" (ürünleri) getirir.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Bu siparişin durum geçmişini döndürür.
     */
    public function history(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id');
    }
}
