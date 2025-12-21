<?php

namespace App\Filament\Resources\Products\Schemas;

// --- DOĞRU 'use' BİLDİRİMLERİ ---
// TÜM bileşenler 'Filament\Forms\Components' altından gelir
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

// Slug utility Set IS under Schemas (Bu özel bir durum)
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

// Fonksiyon imzası Schema kullanır
use Filament\Schemas\Schema; // <-- Form DEĞİL, Schema KULLANILIR

class ProductForm
{
    // --- DOĞRU FONKSİYON İMZASI ---
    public static function configure(Schema $schema): Schema // <-- Form DEĞİL, Schema KULLANILIR
    {
        // --- DOĞRU ÇAĞRI ---
        return $schema
            // ->schema DEĞİL, ->components kullanılır
            ->components([
                TextInput::make('name')
                    ->label('Ürün Adı')
                    ->required()->maxLength(255)->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->label('URL (Slug)')->required()->maxLength(255)
                    ->unique(table: 'products', column: 'slug', ignoreRecord: true),

                Select::make('categories')
                    ->label('Kategoriler')
                    ->multiple()
                    ->relationship(name: 'categories', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('vat_rate')
                    ->label('KDV Oranı (%)')->options([10 => '%10', 20 => '%20'])
                    ->default(10)->required(),

                Toggle::make('should_track_stock')
                    ->label('Stok Takibi Yapılsın mı?')
                    ->helperText('Eğer stok takibi yapılmayacaksa (örn: hizmet), kapatın.')
                    ->default(true),

                Textarea::make('meta_keywords')
                    ->label('SEO - Anahtar Kelimeler')->helperText('Virgül ile ayırarak giriniz.')
                    ->columnSpanFull(),

                Textarea::make('meta_description')
                    ->label('SEO - Açıklama')->helperText('Arama motorlarında görünecek kısa açıklama.')
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('Ürün Açıklaması')->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Ürün Yayında mı?')->default(true),

                TextInput::make('original_image_url')
                    ->label('Resim Linki (Manuel)')
                    ->helperText('Resim yüklemede sorun yaşıyorsanız buraya link girebilirsiniz. Bu link kullanılarak resim sunucuya indirilecektir.')
                    ->url()
                    ->columnSpanFull(),

                SpatieMediaLibraryFileUpload::make('featured_image')
                    ->label('Ana Ürün Resmi')->collection('product-images')
                    ->image()->imageEditor()->responsiveImages()->columnSpanFull(),

                SpatieMediaLibraryFileUpload::make('gallery')
                    ->label('Ürün Galerisi')->collection('product-gallery')
                    ->multiple()->image()->imageEditor()->responsiveImages()->columnSpanFull(),
            ]);
    }
}