<?php

namespace App\Filament\Resources\Products\RelationManagers;

// --- GEREKLİ TÜM 'use' BİLDİRİMLERİ (SADELEŞTİRİLDİ) ---
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\Model;

// 'RelationManager' Schema sınıfını 'Filament\Schemas' altından bekler.
use Filament\Schemas\Schema; 
// 'Get' sınıfı kaldırıldı.

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextInputColumn; 
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;


class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants'; 
    protected static ?string $modelLabel = 'Varyant';
    protected static ?string $pluralModelLabel = 'Varyantlar';

    public ?string $tempFeaturedImageUrl = null;

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

    // --- FORM GÜNCELLEMESİ (KISITLAMALAR KALDIRILDI) ---
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('color_name')
                    ->label('Renk Adı 1')
                    ->maxLength(255)
                    ->required()
                    ->live(onBlur: true)
                    ->helperText('Önce renk adını girin, sonra bedenleri seçin'),

                TextInput::make('color_name_2')
                    ->label('Renk Adı 2')
                    ->maxLength(255)
                    ->nullable()
                    ->live(onBlur: true),

                ColorPicker::make('color_code')
                    ->label('Renk Kodu 1'),

                ColorPicker::make('color_code_2')
                    ->label('Renk Kodu 2'),

                CheckboxList::make('sizes')
                    // ... (içerik aynı) ...
                    ->options($this->getSizeOptions())
                    ->required()
                    ->columns(3)
                    ->helperText('Bu renge ait tüm bedenleri seçin. Her beden için ayrı kayıt oluşturulacak.')
                    ->visibleOn('create'),
                
                Select::make('size')
                    // ... (içerik aynı) ...
                    ->options($this->getSizeOptions())
                    ->required()
                    ->searchable()
                    ->placeholder('Beden Seçiniz')
                    ->visibleOn('edit'),

                // 4. Normal Fiyat (Sadeleştirildi)
                TextInput::make('price')
                    ->label('Normal Fiyat (Kuruş)')
                    ->helperText('Örn: 199.99 TL için 19999')
                    ->required()
                    ->numeric()
                    ->live(onBlur: true), // 'live()' kaldırıldı, 'onBlur' yeterli

                // 5. İNDİRİMLİ FİYAT (KISITLAMA KALDIRILDI)
                TextInput::make('sale_price')
                    ->label('İndirimli Fiyat (Kuruş)')
                    ->helperText('Boş bırakırsanız indirim uygulanmaz.') // Yardım metni güncellendi
                    ->numeric()
                    ->nullable()
                    // Kurallar sadeleştirildi:
                    ->rules([
                        'nullable',
                        'numeric',
                        'min:0', // Sadece 0'dan büyük olsun
                    ]),

                // 6. Bayi Fiyatı
                TextInput::make('bayii_price')
                    ->label('Bayi Fiyatı (Kuruş)')
                    ->helperText('Bayiye özel toptan fiyat (kuruş)')
                    ->numeric()
                    ->nullable()
                    ->live(onBlur: true),

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

                TextInput::make('barcode')
                    ->label('Barkod')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('featured_image_url')
                    ->label('Resim Linki ile Yükle (Alternatif)')
                    ->helperText('Dosya yükleme çalışmıyorsa burayı kullanın.')
                    ->url()
                    ->columnSpanFull(),

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

    // --- TABLO METODU (KISITLAMA KALDIRILDI) ---
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('size')
            ->columns([
                TextColumn::make('size')->label('Beden')->searchable(),
                TextColumn::make('color_name')->label('Renk')->searchable()->placeholder('Yok'),
                ColorColumn::make('color_code')->label('Renk 1'),
                ColorColumn::make('color_code_2')->label('Renk 2'),

                TextInputColumn::make('price')->label('Normal Fiyat (Kuruş)')
                    ->rules(['required', 'numeric', 'min:0'])->sortable(),

                // İNDİRİMLİ FİYAT SÜTUNU (KISITLAMA KALDIRILDI)
                TextInputColumn::make('sale_price')
                    ->label('İndirimli Fiyat (Kuruş)')
                    // 'lte:price' kuralı kaldırıldı
                    ->rules(['nullable', 'numeric', 'min:0']) 
                    ->placeholder('İndirim Yok')
                    ->sortable(),

                TextInputColumn::make('bayii_price')->label('Bayi Fiyatı (Kuruş)')
                    ->rules(['nullable', 'numeric', 'min:0'])->sortable()->placeholder('Yok')
                    ->toggleable(isToggledHiddenByDefault: true), 

                TextInputColumn::make('stock')->label('Stok')
                    ->rules(['required', 'numeric', 'min:0'])->sortable(),
                
                TextColumn::make('min_quantity')->label('Min. Adet')->numeric()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sku')->label('SKU')->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('barcode')->label('Barkod')->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([ 
                CreateAction::make()
                    ->using(function (array $data, string $model): Model {
                        // ... (CreateAction içeriği aynı kalacak) ...
                        $sizes = $data['sizes'] ?? [];
                        $productId = $this->getOwnerRecord()->id;
                        
                        if (empty($sizes)) {
                            throw new \Exception('En az bir beden seçmelisiniz!');
                        }
                        
                        $createdVariants = [];
                        $variantImage = $data['variant_image'] ?? null;
                        $featuredImageUrl = $data['featured_image_url'] ?? null;
                        
                        foreach ($sizes as $size) {
                            $variantData = [
                                'product_id' => $productId,
                                'size' => $size,
                                'color_name' => $data['color_name'] ?? null,
                                'color_code' => $data['color_code'] ?? null,
                                'color_name_2' => $data['color_name_2'] ?? null,
                                'color_code_2' => $data['color_code_2'] ?? null,
                                'price' => $data['price'] ?? 0,
                                'sale_price' => $data['sale_price'] ?? null,
                                'bayii_price' => $data['bayii_price'] ?? null,
                                'stock' => $data['stock'] ?? 0,
                                'min_quantity' => $data['min_quantity'] ?? 1,
                                'barcode' => $data['barcode'] ?? null,
                            ];
                            
                            $variant = $model::create($variantData);
                            
                            if ($variantImage) {
                                // 
                            }

                            if (! empty($featuredImageUrl)) {
                                try {
                                    $variant->addMediaFromUrl($featuredImageUrl)
                                        ->toMediaCollection('variant-images');
                                } catch (\Exception $e) {
                                    // Sadece ilk hatada bildirim gösterelim, döngüde spam olmasın
                                    if (count($createdVariants) === 0) {
                                        Notification::make()
                                            ->title('Resim İndirilemedi')
                                            ->body('Hata: ' . $e->getMessage())
                                            ->danger()
                                            ->send();
                                    }
                                }
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
            ->recordActions([
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $this->tempFeaturedImageUrl = $data['featured_image_url'] ?? null;
                        unset($data['featured_image_url']);
                        return $data;
                    })
                    ->after(function ($record) {
                        if (! empty($this->tempFeaturedImageUrl)) {
                            try {
                                $record->addMediaFromUrl($this->tempFeaturedImageUrl)
                                    ->toMediaCollection('variant-images');
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Resim İndirilemedi')
                                    ->body('Hata: ' . $e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                            // Temizle
                            $this->tempFeaturedImageUrl = null;
                        }
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([ BulkActionGroup::make([ DeleteBulkAction::make(), ]), ]);
    }
}