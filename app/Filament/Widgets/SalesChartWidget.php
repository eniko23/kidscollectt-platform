<?php

namespace App\Filament\Widgets;

use App\Models\Order; // Sipariş modelimizi kullanacağız
use Carbon\Carbon; // Tarih işlemleri için Carbon kütüphanesi
use Filament\Widgets\ChartWidget;
namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB; // <-- BU SATIRI EKLE

class SalesChartWidget extends ChartWidget
{
    // Grafik Başlığı
    protected ?string $heading = 'Son 7 Günlük Satışlar (TL)'; // <-- 'static' kelimesini sildik

    // Grafiğin ne sıklıkla güncelleneceği (opsiyonel)
    protected ?string $pollingInterval = '30s'; // <-- 'static' kelimesini sildik

    /**
     * Grafiğin verilerini hazırlar.
     *
     * @return array<string, mixed>
     */
    protected function getData(): array
{
    // 1. Son 7 günün satış verilerini veritabanından çek
    $data = Order::where('created_at', '>=', now()->subDays(6))
        // ->groupByDate('created_at') // <-- HATALI KOD BUYDU
        ->groupBy(DB::raw('DATE(created_at)')) // <-- DOĞRUSU BU (Tarihe göre grupla)
        ->selectRaw('DATE(created_at) as date, SUM(total_price) as aggregate')
        ->pluck('aggregate', 'date');

    // 2. Grafiğin etiketlerini ve verilerini hazırla (Bu kısım zaten doğruydu)
    $labels = [];
    $salesData = [];

    // 3. Son 7 gün için döngü başlat
    foreach (range(6, 0) as $i) {
        $date = now()->subDays($i);

        // X eksenine tarihi ekle (Örn: "Eki 31")
        $labels[] = $date->format('M d'); 

        // Y eksenine veriyi ekle
        $salesData[] = ($data[$date->format('Y-m-d')] ?? 0) / 100;
    }

    // 4. Veriyi Filament'in istediği formatta döndür
    return [
        'datasets' => [
            [
                'label' => 'Satışlar (TL)',
                'data' => $salesData,
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'borderColor' => 'rgb(75, 192, 192)',
                'tension' => 0.1,
            ],
        ],
        'labels' => $labels,
    ];
}

    protected function getType(): string
    {
        return 'line'; // Grafik tipi: Çizgi
    }
}