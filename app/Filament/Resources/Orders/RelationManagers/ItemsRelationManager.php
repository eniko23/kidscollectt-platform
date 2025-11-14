<?php

namespace App\Filament\Resources\Orders\RelationManagers;

// --- GEREKLİ TÜM 'use' BİLDİRİMLERİ ---
use Filament\Forms\Components\Select; 
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema; 
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

// --- YENİ EKLENDİ (Özel etiketler için) ---
use App\Models\ProductVariant; 

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $modelLabel = 'Sipariş Kalemi';
    protected static ?string $pluralModelLabel = 'Sipariş Kalemleri';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                // --- AÇILIR MENÜ DÜZELTMESİ BURADA ---
                Select::make('product_variant_id')
                    ->label('Ürün Varyantı')
                    
                    // İlişkiyi 'product' (ana ürün) ile birlikte yüklüyoruz (N+1 sorgu sorununu önler)
                    ->relationship(
                        name: 'variant', 
                        // titleAttribute'u sildik, çünkü SKU'ya güvenmiyoruz
                        modifyQueryUsing: fn ($query) => $query->with('product') 
                    )
                    
                    // Açılır menüdeki her bir seçenek için özel bir etiket (label) oluşturuyoruz
                    ->getOptionLabelFromRecordUsing(function (ProductVariant $record): string {
                        // Ana ürünün adını al
                        $label = $record->product->name ?? 'Ürün Bulunamadı'; 
                        
                        // Renk ve Beden bilgilerini parantez içinde ekle
                        if ($record->color_name) {
                            $label .= ' (' . $record->color_name;
                            if ($record->size) {
                                $label .= ' / ' . $record->size;
                            }
                            $label .= ')';
                        } elseif ($record->size) {
                            $label .= ' (' . $record->size . ')';
                        }
                        
                        // SKU'yu da sona ekleyelim (hata vermeyecek şekilde)
                        $label .= ' [SKU: ' . ($record->sku ?? 'TANIMSIZ') . ']';

                        return $label;
                    })
                    
                    // Artık ürün adına, rengine veya bedenine göre arama yapabilirsin
                    ->searchable(['sku', 'color_name', 'size', 'product.name'])
                    ->preload()
                    ->required()
                    ->columnSpanFull(),
                // --- DÜZELTME BİTTİ ---

                TextInput::make('quantity')
                    ->label('Adet')
                    ->required()
                    ->numeric()
                    ->default(1),

                TextInput::make('price')
                    ->label('Birim Fiyat (Kuruş)')
                    ->helperText('Satın alındığı andaki tekil ürün fiyatı (kuruş cinsinden)')
                    ->required()
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                
                // --- TABLO DÜZELTMESİ BURADA ---
                // Artık "variant.name" (hatalı) yerine, 'variant' ilişkisi üzerinden
                // 'product' (ana ürün) ilişkisine gidip 'name' (ürün adı) sütununu gösteriyoruz.
                TextColumn::make('variant.product.name')
                    ->label('Ürün Adı')
                    ->searchable()
                    ->sortable(),
                
                // Renk ve Beden'i ayrı sütunlarda gösteriyoruz
                TextColumn::make('variant.color_name')
                    ->label('Renk')
                    ->searchable(),
                
                TextColumn::make('variant.size')
                    ->label('Beden')
                    ->searchable(),
                // --- DÜZELTME BİTTİ ---

                TextColumn::make('quantity')
                    ->label('Adet')
                    ->numeric(),

                TextColumn::make('price')
                    ->label('Birim Fiyat')
                    ->numeric()
                    ->money('TRY') // Kuruşları (19999) -> 199,99 TL'ye çevir
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}