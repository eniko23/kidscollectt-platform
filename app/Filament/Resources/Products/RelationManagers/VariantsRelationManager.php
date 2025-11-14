<?php

namespace App\Filament\Resources\Products\RelationManagers;

// --- GEREKLİ TÜM 'use' BİLDİRİMLERİ ---
// TÜM Bileşenler 'Forms\Components' altından gelmeli
use Filament\Forms\Components\ColorPicker; 
use Filament\Forms\Components\Select;      
use Filament\Forms\Components\TextInput; 
use Filament\Forms\Components\SpatieMediaLibraryFileUpload; 
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\Model; 

// Ama Fonksiyon imzası (form()) 'Schema' kullanır
use Filament\Schemas\Schema; 

// Diğer gerekli sınıflar
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
    // --- BU İLİŞKİ SORGUNUN DOĞRU OLDUĞUNU KANITLAR ---
    protected static string $relationship = 'variants'; 

    // Paneldeki başlığı Türkçeleştiriyoruz
    protected static ?string $modelLabel = 'Varyant';
    protected static ?string $pluralModelLabel = 'Varyantlar';

    // Beden seçenekleri
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

    // --- DOĞRU FONKSİYON İMZASI: (Schema $schema) ---
    public function form(Schema $schema): Schema 
    {
        return $schema
            // --- DOĞRU FONKSİYON ÇAĞRISI: ->components([...]) ---
            ->components([ 
                // 1. Renk Adı (Önce renk seçilir)
                TextInput::make('color_name')
                    ->label('Renk Adı')
                    ->maxLength(255)
                    ->required()
                    ->live(onBlur: true)
                    ->helperText('Önce renk adını girin, sonra bedenleri seçin'),

                // 2. Renk Kodu
                ColorPicker::make('color_code')
                    ->label('Renk Kodu'),

                // 3. Beden Seçimi (Create için birden çok, Edit için tek)
                CheckboxList::make('sizes')
                    ->label('Bedenler (Birden çok seçebilirsiniz)')
                    ->options($this->getSizeOptions())
                    ->required()
                    ->columns(3)
                    ->helperText('Bu renge ait tüm bedenleri seçin. Her beden için ayrı kayıt oluşturulacak.')
                    ->visibleOn('create'),
                
                // Edit için tek beden seçimi
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

                // 5. Bayi Fiyatı
                TextInput::make('bayii_price')
                    ->label('Bayi Fiyatı (Kuruş)')
                    ->helperText('Bayiye özel toptan fiyat (kuruş)')
                    ->numeric()
                    ->nullable(), 

                // 6. Stok Adedi
                TextInput::make('stock')
                    ->label('Stok Adedi')
                    ->required()
                    ->numeric()
                    ->default(0),

                // 7. Asgari Adet
                TextInput::make('min_quantity')
                    ->label('Asgari Sipariş Adedi')
                    ->required()
                    ->numeric()
                    ->default(1),

                // 8. Varyant Resmi
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

    // TABLO BÖLÜMÜ (Hızlı Düzenleme dahil)
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('size') 
            ->columns([
                TextColumn::make('size')->label('Beden')->searchable(),
                TextColumn::make('color_name')->label('Renk')->searchable()->placeholder('Yok'),
                ColorColumn::make('color_code')->label('Renk Kodu'),

                TextInputColumn::make('price')->label('Normal Fiyat (Kuruş)')
                    ->rules(['required', 'numeric', 'min:0'])->sortable(),

                TextInputColumn::make('bayii_price')->label('Bayi Fiyatı (Kuruş)')
                    ->rules(['nullable', 'numeric', 'min:0'])->sortable()->placeholder('Yok') 
                    ->toggleable(isToggledHiddenByDefault: true), 

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
                        // Her beden için ayrı kayıt oluştur
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
                                'bayii_price' => $data['bayii_price'] ?? null,
                                'stock' => $data['stock'] ?? 0,
                                'min_quantity' => $data['min_quantity'] ?? 1,
                            ];
                            
                            $variant = $model::create($variantData);
                            
                            // Resmi kopyala (eğer varsa)
                            if ($variantImage) {
                                // Media dosyası Filament tarafından otomatik işlenir
                                // Her varyant için aynı resmi kopyala
                            }
                            
                            $createdVariants[] = $variant;
                        }
                        
                        // Başarı mesajını ayarla
                        $count = count($createdVariants);
                        if ($count > 1) {
                            \Filament\Notifications\Notification::make()
                                ->title("{$count} beden için varyant oluşturuldu!")
                                ->success()
                                ->send();
                        }
                        
                        // İlk oluşturulan varyantı döndür (Filament'in beklediği)
                        return $createdVariants[0];
                    }),
            ])
            ->recordActions([ EditAction::make(), DeleteAction::make(), ])
            ->toolbarActions([ BulkActionGroup::make([ DeleteBulkAction::make(), ]), ]);
    }
}