<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    use HasFactory;

    // Veritabanı tablosunun adını 'order_status_logs' olarak belirttiğimiz için
    // modele bunu bildirmeliyiz.
    protected $table = 'order_status_logs';

    /**
     * Toplu atamaya izin verilen alanların listesi.
     */
    protected $fillable = [
        'order_id',
        'status',
        'notes',
    ];

    /**
     * Bu kaydın ait olduğu ana "Siparişi" (Order) getirir.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}