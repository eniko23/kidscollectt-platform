<?php

namespace App\Filament\Resources\Coupons\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CouponsTable
{
    public static function configure(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('code')
                ->label('Kupon Kodu')
                ->searchable(),

            // 'type' ve 'value' Sütunlarını Akıllı Bir Sütunda Birleştirme
            TextColumn::make('value')
                ->label('İndirim Değeri')
                ->formatStateUsing(function ($state, $record) {
                    // $record, o anki 'Coupon' modelidir
                    if ($record->type === 'percentage') {
                        return '%' . $state; // 'percentage' ise: %10
                    }
                    // 'fixed' ise, kuruşu TL'ye çevir
                    return ($state / 100) . ' TL'; 
                })
                ->sortable(),

            TextColumn::make('min_amount')
                ->label('Min. Sepet Tutarı')
                ->money('TRY') // Kuruşları (100000) -> 1.000,00 TL'ye çevir
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true), // Varsayılan gizli

            TextColumn::make('usage_limit')
                ->label('Kullanım Limiti')
                ->numeric()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true), // Varsayılan gizli

            TextColumn::make('expires_at')
                ->label('Son Geçerlilik Tarihi')
                ->dateTime()
                ->sortable(),
        ]);
}
}
