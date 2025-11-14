<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\SelectColumn;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 🧍 MÜŞTERİ
                TextColumn::make('user.name')
                    ->label('Müşteri')
                    ->searchable(),

                // 📦 DURUM (RENKLİ ROZET + TÜRKÇELEŞTİRME)
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',          // Beklemede
                        'pending_payment' => 'info',     // Ödeme Bekleniyor
                        'processing' => 'info',          // Hazırlanıyor
                        'shipped' => 'primary',          // Kargolandı
                        'completed' => 'success',        // Tamamlandı
                        'cancelled' => 'danger',         // İptal Edildi
                        default => 'secondary',          // Bilinmeyen durum
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Onay Bekliyor',
                        'pending_payment' => 'Ödeme Bekleniyor',
                        'processing' => 'Hazırlanıyor',
                        'shipped' => 'Kargoya Verildi',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal Edildi',
                        default => ucfirst($state),
                    })
                    ->searchable(),

                // 💳 ÖDEME YÖNTEMİ (BOŞSA KAPIDA ÖDEME)
                TextColumn::make('payment_method')
                    ->label('Ödeme Yöntemi')
                    ->badge()
                    ->color(fn (string|null $state): string => match ($state) {
                        'credit_card' => 'primary',          // Mavi
                        'cash_on_delivery' => 'success',     // Yeşil
                        null => 'warning',                   // Boşsa sarı
                        '' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string|null $state): string => match ($state) {
                        'credit_card' => 'Kredi Kartı',
                        'cash_on_delivery' => 'Kapıda Ödeme',
                        null => 'Kapıda Ödeme',  // 💥 Boşsa bu yazacak
                        '' => 'Kapıda Ödeme',
                        default => ucfirst($state),
                    })
                    ->searchable(),

                // 💰 TOPLAM TUTAR
                TextColumn::make('total_price')
                    ->label('Toplam Tutar')
                    ->numeric()
                    ->money('TRY')
                    ->sortable(),

                // 📅 TARİH + SAAT (Türkçe biçim)
                TextColumn::make('created_at')
                    ->label('Sipariş Tarihi')
                    ->dateTime('d.m.Y H:i') // 💥 Gün.Ay.Yıl Saat:Dakika
                    ->sortable(),
            ]);
    }
}
