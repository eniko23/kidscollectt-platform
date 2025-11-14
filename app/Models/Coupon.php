<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    /**
     * Toplu atamaya izin verilen alanların listesi.
     */
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_amount',
        'usage_limit',
        'expires_at',
    ];

    /**
     * Alanların tiplerini belirleme.
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];
}