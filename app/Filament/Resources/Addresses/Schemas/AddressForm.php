<?php

namespace App\Filament\Resources\Addresses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Checkbox;

// Düzen (Layout) Bileşenleri (Senin bulduğun gibi 'Schemas' altında)
use Filament\Schemas\Components\Section;

class AddressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Adres Sahibi ve Etiketi')
                    ->schema([
                        // 'user_id' (Metin Kutusu) YERİNE AÇILIR MENÜ
                        Select::make('user_id')
                            ->label('Müşteri')
                            ->relationship('user', 'email') // 'user' ilişkisine git, 'email'i göster
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('label')
                            ->label('Adres Etiketi')
                            ->helperText('Örn: Ev Adresi, İş Adresi')
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Alıcı Bilgileri')
                    ->schema([
                        TextInput::make('first_name')
                            ->label('Alıcı Adı')
                            ->required()
                            ->maxLength(255),
                        
                        TextInput::make('last_name')
                            ->label('Alıcı Soyadı')
                            ->required()
                            ->maxLength(255),
                        
                        TextInput::make('phone')
                            ->label('Telefon')
                            ->tel()
                            ->maxLength(255),
                    ])->columns(3),

                Section::make('Adres Detayları')
                    ->schema([
                        TextInput::make('address_line_1')
                            ->label('Adres Satırı 1')
                            ->required()
                            ->columnSpanFull(), // Tam genişlik

                        TextInput::make('address_line_2')
                            ->label('Adres Satırı 2 (Opsiyonel)')
                            ->columnSpanFull(), // Tam genişlik

                        TextInput::make('district')
                            ->label('İlçe / Semt')
                            ->required(),

                        TextInput::make('city')
                            ->label('Şehir')
                            ->required(),
                        
                        TextInput::make('country')
                            ->label('Ülke')
                            ->required()
                            ->default('Turkey'),
                    ])->columns(3),

                Section::make('Varsayılan Ayarlar')
                    ->schema([
                        Toggle::make('is_default_shipping')
                            ->label('Varsayılan Teslimat Adresi')
                            ->helperText('Bu adres, müşterinin varsayılan teslimat adresi olarak seçilsin mi?'),
                        
                        Toggle::make('is_default_billing')
                            ->label('Varsayılan Fatura Adresi')
                            ->helperText('Bu adres, müşterinin varsayılan fatura adresi olarak seçilsin mi?'),
                    ])->columns(2),
            ]);
    }
}
