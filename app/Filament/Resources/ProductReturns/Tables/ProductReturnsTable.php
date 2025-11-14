<?php

namespace App\Filament\Resources\ProductReturns\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductReturnsTable
{
    public static function configure(Table $table): Table
{
    return $table
        ->columns([
            // user_id YERİNE user.email KULLANIYORUZ
            TextColumn::make('user.email')
                ->label('Müşteri E-postası')
                ->searchable(),

            // order_id YERİNE order.id KULLANIYORUZ
            TextColumn::make('order.id')
                ->label('Sipariş No')
                ->searchable(),

            // variant_id YERİNE variant.name (veya sku) KULLANIYORUZ
            TextColumn::make('variant.sku') // SKU daha belirleyici olabilir
                ->label('İade Edilen Ürün (SKU)')
                ->searchable(),

            TextColumn::make('quantity')
                ->label('Adet')
                ->numeric(),

            // 'status' (Metin) YERİNE RENKLİ ETİKET (Badge)
            TextColumn::make('status')
                ->label('Durum')
                ->badge() // Etiket olarak göster
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'warning', // Talep Alındı -> Sarı
                    'processing' => 'info',    // İnceleniyor -> Mavi
                    'approved' => 'success', // Onaylandı -> Yeşil
                    'rejected' => 'danger',   // Reddedildi -> Kırmızı
                    default => 'gray',
                })
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'pending' => 'Talep Alındı',
                    'processing' => 'İnceleniyor',
                    'approved' => 'Onaylandı',
                    'rejected' => 'Reddedildi',
                    default => $state,
                })
                ->searchable(),

            TextColumn::make('created_at')
                ->label('Talep Tarihi')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ]);
}
}
