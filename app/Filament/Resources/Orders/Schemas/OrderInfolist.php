<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ❗❗ YENİ EKLENEN BLOK (ÖDEME YÖNTEMİ) ❗❗
                TextEntry::make('payment_method')
                    ->label('Ödeme Yöntemi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'credit_card' => 'primary',
                        'cash_on_delivery' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'credit_card' => 'Kredi Kartı',
                        'cash_on_delivery' => 'Kapıda Ödeme',
                        default => $state,
                    }),
                // ❗❗ YENİ BLOK BİTTİ ❗❗

                TextEntry::make('created_at')
                    ->label('Sipariş Tarihi') // Label ekledim
                    ->dateTime()
                    ->placeholder('-'),
                
                TextEntry::make('updated_at')
                    ->label('Son Güncelleme') // Label ekledim
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}