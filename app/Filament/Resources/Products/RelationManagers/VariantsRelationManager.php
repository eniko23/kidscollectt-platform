<?php

namespace App\Filament\Resources\Products\RelationManagers;

// --- GEREKLİ TÜM 'use' BİLDİRİMLERİ ---
// ... (Mevcut 'use' bildirimleriniz aynı kalacak)
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Schema; // Fonksiyon imzası (form()) 'Schema' kullanır
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextInputColumn; // Hızlı Düzenleme için
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;


class VariantsRelationManager extends RelationManager
{
    // ... (protected static string $relationship vb. aynı kalacak) ...
    protected static string $relationship = 'variants'; 
    protected static ?string $modelLabel = 'Varyant';
    protected static ?string $pluralModelLabel = 'Varyantlar';

    // ... (getSizeOptions() metodu aynı kalacak) ...
    protected function getSizeOptions(): array
    {
        return [

            '2-3 yaş' => '2-3 Yaş', 

            '3-4 yaş' => '3-4 Yaş', 

            '4-5 yaş' => '4-5 Yaş',

            '5-6 yaş' => '5-6 Yaş', 

            '7-8 yaş' => '7-8 Yaş', 

            '9-10 yaş' => '9-10 Yaş',

            '11-12 yaş' => '11-12 Yaş', 

            's' => 'S', 

            'm' => 'M', 

            'l' => 'L',

            'xl' => 'XL', 

            'xxl' => 'XXL',

        ];
    }

    // --- FORM GÜNCELLEMESİ ---
    // --- FORM GÜNCELLEMESİ ---
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ... (color_name, color_code, sizes, size aynı kalacak) ...
                TextInput::make('color_name')
                    ->label('Renk Adı')
                    ->maxLength(255)
                    ->required()
                    ->live(onBlur: true)
                    ->helperText('Önce renk adını girin, sonra bedenleri seçin'),

                ColorPicker::make('color_code')
                    ->label('Renk Kodu'),

                CheckboxList::make('sizes')
                    ->label('Bedenler (Birden çok seçebilirsiniz)')
                    ->options($this->getSizeOptions())
                    ->required()
                    ->columns(3)
                    ->helperText('Bu renge ait tüm bedenleri seçin. Her beden için ayrı kayıt oluşturulacak.')
                    ->visibleOn('create'),
                
                Select::make('size')
                    ->label('Beden')
                    ->options($this->getSizeOptions())
                    ->required()
                    ->searchable()
                    ->placeholder('Beden Seçiniz')
                    ->visibleOn('edit'),

                // 4. Normal Fiyat
                TextInput::make('price')
                    ->label('Normal Fiyat (Kuruş)')
                    ->helperText('Örn: 199.99 TL için 19999')
                    ->required()
                    ->numeric(),

                // 5. İNDİRİMLİ FİYAT (DÜZELTİLDİ)
                TextInput::make('sale_price')
                    ->label('İndirimli Fiyat (Kuruş)')
                    ->helperText('Boş bırakırsanız indirim uygulanmaz. Normal fiyattan düşük olmalı.')
                    ->numeric()
                    ->nullable()
                    // Kuralı 'rules' içine taşıdık
                    ->rules(['nullable', 'numeric', 'lte:price']) 
                    // Özel hata mesajını burada tanımladık
                    ->validationMessages([
                        'lte' => 'İndirimli fiyat, normal fiyattan düşük veya eşit olmalıdır.',
                    ]),

                // 6. Bayi Fiyatı (Sırası değişti)
                TextInput::make('bayii_price')
                    ->label('Bayi Fiyatı (Kuruş)')
                    ->helperText('Bayiye özel toptan fiyat (kuruş)')
                    ->numeric()
                    ->nullable(), 

                // ... (stock, min_quantity, variant_image aynı kalacak) ...
                TextInput::make('stock')
                    ->label('Stok Adedi')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('min_quantity')
                    ->label('Asgari Sipariş Adedi')
                    ->required()
                    ->numeric()
                    ->default(1),

                SpatieMediaLibraryFileUpload::make('variant_image')
                    ->label('Varyanta Özel Resim')
                    ->collection('variant-images') 
                    ->helperText('Bu renge/varyanta özel resim')
                    ->image()
                    ->imageEditor()
                    ->responsiveImages()
                    ->columnSpanFull(),
            ]);
    }

    // --- TABLO GÜNCELLEMESİ ---
    // --- TABLO GÜNCELLEMESİ ---
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('size')
            ->columns([
                TextColumn::make('size')->label('Beden')->searchable(),
                TextColumn::make('color_name')->label('Renk')->searchable()->placeholder('Yok'),
                ColorColumn::make('color_code')->label('Renk Kodu'),

                // NORMAL FİYAT SÜTUNU
                TextInputColumn::make('price')->label('Normal Fiyat (Kuruş)')
                    ->rules(['required', 'numeric', 'min:0'])->sortable(),

                // İNDİRİMLİ FİYAT SÜTUNU (DÜZELTİLDİ VE SADELEŞTİRİLDİ)
                TextInputColumn::make('sale_price')
                    ->label('İndirimli Fiyat (Kuruş)')
                    // 'lte:price' kuralı, 'price' sütunundaki değere göre kontrol sağlar
                    ->rules(['nullable', 'numeric', 'min:0', 'lte:price'])
                    ->validationMessages([
                        'lte' => 'İndirimli fiyat normal fiyattan düşük olmalı.',
                    ])
                    ->placeholder('İndirim Yok')
                    ->sortable(),

                // BAYİ FİYATI SÜTUNU
                TextInputColumn::make('bayii_price')->label('Bayi Fiyatı (Kuruş)')
                    ->rules(['nullable', 'numeric', 'min:0'])->sortable()->placeholder('Yok')
                    ->toggleable(isToggledHiddenByDefault: true), 

                // ... (stock, min_quantity, sku aynı kalacak) ...
                TextInputColumn::make('stock')->label('Stok')
                    ->rules(['required', 'numeric', 'min:0'])->sortable(),
                
                TextColumn::make('min_quantity')->label('Min. Adet')->numeric()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sku')->label('SKU')->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([ 
                CreateAction::make()
                    ->using(function (array $data, string $model): Model {
                        // ... (CreateAction içeriği önceki gibi aynı kalabilir,
                        // 'sale_price' => $data['sale_price'] ?? null, satırı zaten ekliydi)

                        $sizes = $data['sizes'] ?? [];
                        $productId = $this->getOwnerRecord()->id;
                        
                        if (empty($sizes)) {
                            throw new \Exception('En az bir beden seçmelisiniz!');
                        }
                        
                        $createdVariants = [];
                        $variantImage = $data['variant_image'] ?? null;
                        
                        foreach ($sizes as $size) {
                            $variantData = [
                                'product_id' => $productId,
                                'size' => $size,
                                'color_name' => $data['color_name'] ?? null,
                                'color_code' => $data['color_code'] ?? null,
                                'price' => $data['price'] ?? 0,
                                'sale_price' => $data['sale_price'] ?? null, // <-- Bu satır zaten ekliydi
                                'bayii_price' => $data['bayii_price'] ?? null,
                                'stock' => $data['stock'] ?? 0,
                                'min_quantity' => $data['min_quantity'] ?? 1,
                            ];
                            
                            $variant = $model::create($variantData);
                            
                            if ($variantImage) {
                                // Media dosyası Filament tarafından otomatik işlenir
                            }
                            
                            $createdVariants[] = $variant;
                        }
                        
                        $count = count($createdVariants);
                        if ($count > 1) {
                            \Filament\Notifications\Notification::make()
                                ->title("{$count} beden için varyant oluşturuldu!")
                                ->success()
                                ->send();
                        }
                        
                        return $createdVariants[0];
                    }),
            ])
            ->recordActions([ EditAction::make(), DeleteAction::make(), ])
            ->toolbarActions([ BulkActionGroup::make([ DeleteBulkAction::make(), ]), ]);
    }
}