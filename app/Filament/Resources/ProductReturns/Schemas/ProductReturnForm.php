<?php

namespace App\Filament\Resources\ProductReturns\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section; 

class ProductReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('İlişkili Kayıtlar')
                    ->description('Bu iade talebinin hangi siparişe ve müşteriye ait olduğunu seçin.')
                    ->schema([
                        // 'user_id' (Metin Kutusu) YERİNE AÇILIR MENÜ
                        Select::make('user_id')
                            ->label('Müşteri')
                            ->relationship('user', 'email') // 'user' ilişkisine git, 'email'i göster
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        // 'order_id' (Metin Kutusu) YERİNE AÇILIR MENÜ
                        Select::make('order_id')
                            ->label('Sipariş ID')
                            ->relationship('order', 'id') // 'order' ilişkisine git, 'id'yi göster
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('product_variant_id')
                            ->label('İade Edilen Ürün (Varyant)')
                            ->relationship(name: 'variant', titleAttribute: 'sku') 
                            ->helperText('Ürünün SKU kodunu seçin. (Örn: SAN-04-008)')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(3), // Bu bölüm 3 sütunlu olsun

                Section::make('İade Detayları ve Durumu')
                    ->schema([
                        // 'status' (Metin Kutusu) YERİNE AÇILIR MENÜ
                        Select::make('status')
                            ->label('İade Durumu')
                            ->options([
                                'pending' => 'Talep Alındı',
                                'processing' => 'İnceleniyor',
                                'approved' => 'Onaylandı',
                                'rejected' => 'Reddedildi',
                            ])
                            ->default('pending')
                            ->required(),
                        
                        TextInput::make('quantity')
                            ->label('İade Adedi')
                            ->numeric()
                            ->required()
                            ->default(1),
                        
                        Textarea::make('reason')
                            ->label('Müşteri İade Sebebi')
                            ->helperText('Müşterinin belirttiği iade sebebi.')
                            ->columnSpanFull(),
                        
                        Textarea::make('notes')
                            ->label('Admin Notları')
                            ->helperText('Müşterinin görmeyeceği, operasyonel iç notlar.')
                            ->columnSpanFull(),
                    ])->columns(2), // Bu bölüm 2 sütunlu olsun
            ]);
    }
}
