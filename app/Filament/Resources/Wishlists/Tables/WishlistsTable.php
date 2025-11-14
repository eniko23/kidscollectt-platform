<?php

namespace App\Filament\Resources\Wishlists\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WishlistsTable
{
    public static function configure(Table $table): Table
{
    return $table
        ->columns([
            // user_id YERİNE user.email
            TextColumn::make('user.email')
                ->label('Müşteri E-postası')
                ->searchable(),

            // product_id YERİNE product.name
            TextColumn::make('product.name')
                ->label('Favorideki Ürün')
                ->searchable(),

            TextColumn::make('created_at')
                ->label('Ekleme Tarihi')
                ->dateTime()
                ->sortable(),
        ]);
}
}
