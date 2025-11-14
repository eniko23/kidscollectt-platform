<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->columns([
            SpatieMediaLibraryImageColumn::make('featured_image')
                    ->label('Resim')
                    ->collection('product-images') // Ürün formunda verdiğimiz koleksiyon adı
                    ->conversion('thumb') // Modelde tanımladığımız 'thumb' kopyasını kullan
                    ->circular(),
                    
            // 1. Ürün Adı Sütunu
            TextColumn::make('name')
                ->label('Ürün Adı')
                ->searchable(), // Arama kutusunda bu sütuna göre ara

            // 2. Kategori Adı Sütunu (İlişkili)
            TextColumn::make('category.name') // "category" ilişkisine git, oradaki "name"i al
                ->label('Kategori')
                ->badge() // Şık bir etiket içinde göster
                ->searchable(),

            // 3. Durum Sütunu (Aktif/Pasif)
            IconColumn::make('is_active')
                ->label('Yayında mı?')
                ->boolean(), // true ise tik (✓), false ise çarpı (X) göster

            // 4. Tarih Sütunları (Gizli)
            TextColumn::make('created_at')
                ->label('Oluşturma Tarihi')
                ->dateTime()
                ->sortable()
                // Varsayılan olarak gizle, kullanıcı isterse açar
                ->toggleable(isToggledHiddenByDefault: true), 

            TextColumn::make('updated_at')
                ->label('Güncelleme Tarihi')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ]);
    }
}
