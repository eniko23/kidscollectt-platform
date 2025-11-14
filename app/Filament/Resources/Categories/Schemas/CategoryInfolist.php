<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;


class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            // Otomatik oluşturulan kodu güncelliyoruz
            TextEntry::make('name')
                ->label('Kategori Adı'), // <-- EKLENDİ

            TextEntry::make('slug')
                ->label('URL (Slug)'), // <-- EKLENDİ

            // Parent Id'yi değil, ilişkinin adını gösteriyoruz
            TextEntry::make('parent.name') 
                ->label('Ana Kategori')
                ->placeholder('Yok'), // Ana kategorisi yoksa "Yok" yazar

            TextEntry::make('created_at')
                ->label('Oluşturma Tarihi')
                ->dateTime('d M Y, H:i'), // Tarihi daha okunaklı formatla

            TextEntry::make('updated_at')
                ->label('Güncelleme Tarihi')
                ->dateTime('d M Y, H:i'), // Tarihi daha okunaklı formatla
        ]);
    }
}
