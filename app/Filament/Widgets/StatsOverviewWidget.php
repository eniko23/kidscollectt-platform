<?php

namespace App\Filament\Widgets;

// --- GEREKLİ MODELLER VE KAYNAKLAR ---
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Users\UserResource;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    /**
     * İstatistik kartlarını tanımlar.
     *
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        // --- 1. Toplam Satışı HESAPLA ---
        // Sadece 'Tamamlandı' (completed) durumundaki siparişlerin toplam tutarını (kuruş) al
        $totalSalesInKurus = Order::where('status', 'completed')->sum('total_price');

        // Kuruşu TL'ye çevir ve formatla (örn: 1.234,50 TL)
        $totalSalesInTL = number_format($totalSalesInKurus / 100, 2, ',', '.') . ' TL';

        // --- 2. KARTLARI OLUŞTUR ---
        return [
            // 1. KART: Toplam Satış (YENİ EKLENDİ - Boşluğu doldurmak için)
            Stat::make('Toplam Satış (Ciro)', $totalSalesInTL) 
                ->description('Tamamlanan siparişlerin KDV dahil toplamı')
                ->descriptionIcon('heroicon-m-banknotes') // Para ikonu
                ->url(OrderResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'completed']]])) // Tıklayınca "Tamamlanan Siparişler" listesine götür
                ->color('primary'), // Ana renk

            // 2. KART: Toplam Müşteri
            Stat::make('Toplam Müşteri', User::count())
                ->description('Tüm kayıtlı bireysel ve bayi müşteriler')
                ->descriptionIcon('heroicon-m-users')
                ->url(UserResource::getUrl('index'))
                ->color('success'), 

            // 3. KART: Toplam Ürünler
            Stat::make('Toplam Ürün', Product::count())
                ->description('Katalogdaki tüm ana ürünler')
                ->descriptionIcon('heroicon-m-archive-box')
                ->url(ProductResource::getUrl('index'))
                ->color('info'),

            // 4. KART: Toplam Siparişler
            Stat::make('Toplam Sipariş', Order::count())
                ->description('Sisteme düşen tüm siparişler')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->url(OrderResource::getUrl('index'))
                ->color('warning'),
        ];
    }
}