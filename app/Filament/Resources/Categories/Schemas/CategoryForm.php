<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;


class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label('Kategori Adı') // 1. SORUN ÇÖZÜLDÜ (Türkçeleştirme)
                ->required()
                ->maxLength(255)
                ->live(onBlur: true) // "live" modu aç, kutudan çıkınca (onBlur) çalış
                
                // 2. SORUN ÇÖZÜLDÜ (Otomatik Slug)
                // "name" alanı her güncellendiğinde, "slug" alanını da güncelle
                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

            TextInput::make('slug')
                ->label('URL (Slug)') // 1. SORUN ÇÖZÜLDÜ
                ->required()
                ->maxLength(255)
                // Bu slug'ın veritabanında başka bir satırda kullanılmadığından emin ol
                // (ignoreRecord: true -> düzenleme yaparken kendisi hariç)
                ->unique(table: 'categories', column: 'slug', ignoreRecord: true), 

            // 3. SORUN ÇÖZÜLDÜ (Açılır Menü)
            Select::make('parent_id')
                ->label('Ana Kategori') // 1. SORUN ÇÖZÜLDÜ
                ->placeholder('Ana Kategori Yok') // Boş seçeneğin metni
                
                // İlişkiyi kur: 'parent' model ilişkisini kullan,
                // seçenek başlığı olarak 'name' sütununu göster
                ->relationship(name: 'parent', titleAttribute: 'name') 
                
                ->searchable() // Kategoriler içinde arama yapabilmeyi sağlar
                ->preload() // Sayfa yüklenince seçenekleri yükler
        ]);
    }
}
