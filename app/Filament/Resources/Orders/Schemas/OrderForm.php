<?php

namespace App\Filament\Resources\Orders\Schemas;

// --- DOĞRU 'use' BİLDİRİMLERİ ---
// NumberInput'ı sildik, çünkü linter'ınız onu bulamıyor.
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
// Fonksiyon imzası için 'Schema' gerekiyor
use Filament\Schemas\Schema; 

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Müşteri (Kullanıcı)')
                    ->relationship(name: 'user', titleAttribute: 'email')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('status')
                    ->label('Sipariş Durumu')
                    ->options([
                        'pending' => 'Beklemede',
                        'processing' => 'Hazırlanıyor',
                        'shipped' => 'Kargolandı',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal Edildi',
                    ])
                    ->default('pending')
                    ->required(),

                // --- DEĞİŞİKLİK BURADA ---
                // NumberInput YERİNE TextInput KULLANIYORUZ
                TextInput::make('total_price')
                    ->label('Toplam Tutar (Kuruş Olarak)')
                    ->helperText('Siparişin KDV dahil toplam tutarı (kuruş cinsinden)')
                    ->numeric() // Sadece sayı girilebilmesini sağlar
                    ->required(),

                Textarea::make('shipping_address')
                    ->label('Kargo Adresi')
                    ->columnSpanFull(), 
            ]);
    }
}