<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ToggleColumn; // Onay durumu için (en iyisi)

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Ad ve Soyadı ayrı sütunlarda, aranabilir
                TextColumn::make('first_name')
                    ->label('Adı')
                    ->searchable(),

                TextColumn::make('last_name')
                    ->label('Soyadı')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable(),

                // 'user_type' (Metin) YERİNE RENKLİ ETİKET (Badge)
                TextColumn::make('user_type')
                    ->label('Müşteri Grubu')
                    ->badge() // Etiket olarak göster
                    ->color(fn (string $state): string => match ($state) {
                        'bireysel' => 'gray',  // Bireysel -> Gri
                        'bayi' => 'success', // Bayi -> Yeşil
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'bireysel' => 'Bireysel', // "bireysel" yerine "Bireysel" yaz
                        'bayi' => 'Bayi',       // "bayi" yerine "Bayi" yaz
                    })
                    ->searchable(),

                // 'is_approved' (0/1) YERİNE AÇ/KAPAT BUTONU
                // BU, FAZ 1'DEKİ EN ÖNEMLİ İYİLEŞTİRME!
                ToggleColumn::make('is_approved')
                    ->label('Bayi Onaylı'),
                
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Varsayılan gizli olsun

                TextColumn::make('company_name')
                    ->label('Firma Adı')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Varsayılan gizli olsun

                TextColumn::make('created_at')
                    ->label('Kayıt Tarihi')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
