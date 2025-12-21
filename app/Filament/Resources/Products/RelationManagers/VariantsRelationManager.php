<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Models\GalleryImage;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Schema; 
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
                    ->options($this->getSizeOptions())
                    ->required()
                    ->columns(3)
                    ->helperText('Bu renge ait tüm bedenleri seçin. Her beden için ayrı kayıt oluşturulacak.')
                    ->visibleOn('create'),
                
                Select::make('size')
                    ->options($this->getSizeOptions())
                    ->required()
                    ->searchable()
                    ->placeholder('Beden Seçiniz')
                    ->visibleOn('edit'),

                TextInput::make('price')
                    ->label('Normal Fiyat (Kuruş)')
                    ->helperText('Örn: 199.99 TL için 19999')
                    ->required()
                    ->numeric()
                    ->live(onBlur: true),

                TextInput::make('sale_price')
                    ->label('İndirimli Fiyat (Kuruş)')
                    ->helperText('Boş bırakırsanız indirim uygulanmaz.')
                    ->numeric()
                    ->nullable()
                    ->rules([
                        'nullable',
                        'numeric',
                        'min:0',
                    ]),

                TextInput::make('bayii_price')
                    ->label('Bayi Fiyatı (Kuruş)')
                    ->helperText('Bayiye özel toptan fiyat (kuruş)')
                    ->numeric()
                    ->nullable()
                    ->live(onBlur: true),

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
                
                // --- GÖRSEL ALANLARI ---

                Select::make('from_gallery_id')
                    ->label('Galeriden Görsel Seç')
                    ->helperText('Daha önce yüklenmiş bir görseli kullanmak için seçin.')
                    ->options(GalleryImage::all()->pluck('title', 'id'))
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),

                TextInput::make('original_image_url')
                    ->label('Resim Linki (Manuel)')
                    ->helperText('Resim yüklemede sorun yaşıyorsanız buraya link girebilirsiniz.')
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('size')
            ->columns([
                TextColumn::make('size')->label('Beden')->searchable(),
                TextColumn::make('color_name')->label('Renk')->searchable()->placeholder('Yok'),
                ColorColumn::make('color_code')->label('Renk 1'),
                ColorColumn::make('color_code_2')->label('Renk 2'),
                TextInputColumn::make('price')->label('Fiyat')->sortable(),
                TextInputColumn::make('stock')->label('Stok')->sortable(),
            ])
            ->headerActions([ 
                CreateAction::make()
                    ->using(function (array $data, string $model): Model {
                        $sizes = $data['sizes'] ?? [];
                        $productId = $this->getOwnerRecord()->id;
                        
                        if (empty($sizes)) {
                            throw new \Exception('En az bir beden seçmelisiniz!');
                        }
                        
                        $createdVariants = [];
                        $featuredImageUrl = $data['original_image_url'] ?? null;
                        $galleryImageId = $data['from_gallery_id'] ?? null;
                        
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
                            
                            // 1. Manuel Link
                            if (! empty($featuredImageUrl)) {
                                try {
                                    $variant->addMediaFromUrl($featuredImageUrl)
                                        ->toMediaCollection('variant-images');
                                } catch (\Exception $e) {
                                    // Sessiz kal veya logla
                                }
                            }
                            
                            // 2. Galeri Seçimi
                            if (! empty($galleryImageId)) {
                                $galleryImage = GalleryImage::find($galleryImageId);
                                if ($galleryImage && $galleryImage->filename) {
                                    try {
                                        $url = asset('uploads/' . $galleryImage->filename);
                                        $variant->addMediaFromUrl($url)
                                            ->toMediaCollection('variant-images');
                                    } catch (\Exception $e) {
                                        // Sessiz kal
                                    }
                                }
                            }
                            
                            $createdVariants[] = $variant;
                        }
                        
                        $count = count($createdVariants);
                        if ($count > 1) {
                            Notification::make()
                                ->title("{$count} beden için varyant oluşturuldu!")
                                ->success()
                                ->send();
                        }
                        
                        return $createdVariants[0];
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->using(function (Model $record, array $data): Model {
                        $featuredImageUrl = $data['original_image_url'] ?? null;
                        $galleryImageId = $data['from_gallery_id'] ?? null;
                        
                        unset($data['original_image_url']);
                        unset($data['from_gallery_id']);
                        
                        $record->update($data);
                        
                        // 1. Manuel Link
                        if (! empty($featuredImageUrl)) {
                            try {
                                $record->addMediaFromUrl($featuredImageUrl)
                                    ->toMediaCollection('variant-images');
                                Notification::make()->title('Resim Güncellendi')->success()->send();
                            } catch (\Exception $e) {
                                Notification::make()->title('Resim İndirilemedi')->danger()->send();
                            }
                        }

                        // 2. Galeri Seçimi
                        if (! empty($galleryImageId)) {
                            $galleryImage = GalleryImage::find($galleryImageId);
                            if ($galleryImage && $galleryImage->filename) {
                                try {
                                    $url = asset('uploads/' . $galleryImage->filename);
                                    $record->addMediaFromUrl($url)
                                        ->toMediaCollection('variant-images');
                                    Notification::make()->title('Galeri Görseli Eklendi')->success()->send();
                                } catch (\Exception $e) {
                                    Notification::make()->title('Hata')->body($e->getMessage())->danger()->send();
                                }
                            }
                        }
                        
                        return $record;
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([ BulkActionGroup::make([ DeleteBulkAction::make(), ]), ]);
    }
}
