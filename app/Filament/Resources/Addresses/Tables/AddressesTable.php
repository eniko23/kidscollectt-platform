<?php

namespace App\Filament\Resources\Addresses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AddressesTable
{
    public static function configure(Table $table): Table
{
    return $table
        ->columns([
            // user_id YERİNE user.email KULLANIYORUZ
            TextColumn::make('user.email')
                ->label('Müşteri E-postası')
                ->searchable()
                ->sortable(),

            TextColumn::make('label')
                ->label('Adres Etiketi')
                ->searchable()
                ->badge(), // Etiket olarak göster (örn: "Ev Adresi")

            // Tam Adresi tek bir sütunda birleştirme
            TextColumn::make('full_address')
                ->label('Tam Adres')
                ->formatStateUsing(fn ($record) => 
                    // Adres Satırı 1, İlçe / Şehir
                    $record->address_line_1 . ', ' . $record->district . ' / ' . $record->city
                )
                ->searchable(['address_line_1', 'district', 'city']), // Birden fazla sütunda ara

            TextColumn::make('phone')
                ->label('Telefon')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true), // Varsayılan gizli

            IconColumn::make('is_default_shipping')
                ->label('Varsayılan Teslimat')
                ->boolean()
                ->toggleable(isToggledHiddenByDefault: true), // Varsayılan gizli

            IconColumn::make('is_default_billing')
                ->label('Varsayılan Fatura')
                ->boolean()
                ->toggleable(isToggledHiddenByDefault: true), // Varsayılan gizli

            TextColumn::make('created_at')
                ->label('Oluşturma Tarihi')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ]);
}
}
