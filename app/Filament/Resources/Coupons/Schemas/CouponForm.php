<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;




class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Temel Bilgiler')
                    ->schema([
                        TextInput::make('code')
                            ->label('Kupon Kodu')
                            ->helperText('Müşterinin gireceği benzersiz kod (örn: YAZ10)')
                            ->required()
                            ->maxLength(255)
                            ->unique(table: 'coupons', column: 'code', ignoreRecord: true), // Benzersiz olmalı

                        Textarea::make('description')
                            ->label('Açıklama')
                            ->helperText('Bu kuponun ne işe yaradığı (Sadece admin görür)')
                            ->columnSpanFull(),
                    ])->columns(1),

                Section::make('İndirim Ayarları')
                    ->schema([
                        // 'type' (Metin Kutusu) YERİNE AÇILIR MENÜ
                        Select::make('type')
                            ->label('İndirim Tipi')
                            ->options([
                                'fixed' => 'Sabit Tutar (TL)',
                                'percentage' => 'Yüzde (%)',
                            ])
                            ->required()
                            ->default('fixed')
                            ->live(), // Bu alanı "canlı" yapar

                        // 'value' (Değer)
                        TextInput::make('value') // NumberInput hatasından kaçınmak için TextInput
                            ->label('İndirim Değeri')
                            ->helperText(fn (callable $get): string => match ($get('type')) {
                                'fixed' => 'Tutarı kuruş olarak girin (örn: 50 TL için 5000)',
                                'percentage' => 'Yüzde değerini girin (örn: 10 için %10)',
                                default => 'Önce indirim tipi seçin',
                            })
                            ->required()
                            ->numeric(),
                    ])->columns(2),

                Section::make('Kullanım Koşulları')
                    ->schema([
                        TextInput::make('min_amount')
                            ->label('Minimum Sepet Tutarı (Kuruş)')
                            ->helperText('Kuponun geçerli olması için gereken min. tutar (örn: 1000 TL için 100000). Boş bırakılırsa limitsiz.')
                            ->numeric()
                            ->nullable(),

                        TextInput::make('usage_limit')
                            ->label('Toplam Kullanım Limiti')
                            ->helperText('Bu kuponun toplam kaç kez kullanılabileceği. Boş bırakılırsa sınırsız.')
                            ->numeric()
                            ->nullable(),

                        DateTimePicker::make('expires_at')
                            ->label('Geçerlilik Tarihi')
                            ->helperText('Kuponun geçerli olacağı son tarih. Boş bırakılırsa süresiz.'),
                    ])->columns(3),
            ]);
    }
}
