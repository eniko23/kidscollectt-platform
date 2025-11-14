<?php

use App\Models\Order;
use Illuminate\Support\Collection;
use function Livewire\Volt\{layout, title, with};

// 1. Layout ve başlık
layout('components.layouts.account');
title('Siparişlerim');

// 2. Blade'e verileri gönder
with(fn () => [
    'orders' => Order::where('user_id', auth()->id())
                      ->latest()
                      ->get()
]);

?>

<div>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">
        Siparişlerim
    </h2>

    <div class="space-y-4">
        @forelse($orders as $order)
            @php
                // 1. Durum için Türkçeleştirme ve Renk Belirleme
                $statusText = '';
                $statusColorClass = '';

                // 2. Duruma göre renk ve yazı seçimi
                switch ($order->status) {
                    case 'pending':
                        $statusText = 'Beklemede';
                        $statusColorClass = 'bg-yellow-100 text-yellow-800';
                        break;
                    case 'processing':
                        $statusText = 'Hazırlanıyor';
                        $statusColorClass = 'bg-blue-100 text-blue-800';
                        break;
                    case 'shipped':
                        $statusText = 'Kargolandı';
                        $statusColorClass = 'bg-indigo-100 text-indigo-800';
                        break;
                    case 'completed':
                        $statusText = 'Tamamlandı';
                        $statusColorClass = 'bg-green-100 text-green-800';
                        break;
                    case 'cancelled':
                        $statusText = 'İptal Edildi';
                        $statusColorClass = 'bg-red-100 text-red-800';
                        break;
                    case 'pending_payment':
                        $statusText = 'Ödeme Bekleniyor';
                        $statusColorClass = 'bg-red-100 text-red-800';
                        break;
                    default:
                        $statusText = ucfirst($order->status);
                        $statusColorClass = 'bg-gray-100 text-gray-800';
                }

                // 3. Tarihi Türkçe formatta göster (örnek: 13 Kasım 2025, 15:42)
                \Carbon\Carbon::setLocale('tr');
                $formattedDate = \Carbon\Carbon::parse($order->created_at)
                    ->translatedFormat('d F Y, H:i');
            @endphp

            <div class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                    <div>
                        <span class="font-bold text-gray-800">
                            Sipariş No: #{{ $order->id }}
                        </span>
                        <span class="ml-0 sm:ml-4 text-sm text-gray-600">
                            {{ $formattedDate }}
                        </span>
                    </div>
                    <span class="text-lg font-bold text-pink-600 mt-2 sm:mt-0">
                        {{ number_format($order->total_price / 100, 2, ',', '.') }} TL
                    </span>
                </div>

                <div class="mt-2">
                    <span class="text-sm font-medium px-3 py-1 rounded-full {{ $statusColorClass }}">
                        {{ $statusText }}
                    </span>
                </div>
            </div>
        @empty
            <p class="text-gray-600">Henüz hiç sipariş vermemişsiniz.</p>
        @endforelse
    </div>
</div>
