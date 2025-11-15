<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

// --- DOĞRU 'use' BİLDİRİMLERİ ---
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section; // 'Section' 'Schemas' altından
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
// use Filament\Forms\Form; // <-- YANLIŞ! Bunu siliyoruz.
use Filament\Schemas\Schema; // <-- DOĞRUSU BU!
use App\Models\Setting; 
use BackedEnum;
use App\Models\Product;

class SiteAyarlari extends Page implements HasForms
{
    use InteractsWithForms; 

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Ayarları';
    protected string $view = 'filament.pages.site-ayarlari'; 

    public ?array $data = []; 

    public function mount(): void
    {
        // Tüm ayarları veritabanından çek ve 'data' dizisine doldur
        $this->data = Setting::pluck('value', 'key')->toArray(); 
        $this->form->fill($this->data);
    }

    /**
     * Formun şemasını (alanlarını) tanımla
     * (Form -> Schema olarak DÜZELTİLDİ)
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Haftanın Fırsatı Ayarları')
                    ->description('Ana sayfadaki geri sayım sayacını ve fırsat ürününü buradan yönetin.')
                    ->schema([ // Section içindeki schema() doğrudur
                        Select::make('featured_product_id')
                            ->label('Fırsat Ürünü Seçin')
                            ->options(Product::pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                        
                        TextInput::make('featured_product_price')
                            ->label('İndirimli Fiyat (Kuruş)')
                            ->numeric()
                            ->helperText('Örn: 199.99 TL için 19999'),
                        
                        DateTimePicker::make('featured_product_expires_at')
                            ->label('Fırsat Bitiş Tarihi'),
                    ])->columns(3),
                
                Section::make('Kargo Ayarları')
                    ->description('Sepet ayarları ve ücretsiz kargo limitleri.')
                    ->schema([
                        TextInput::make('shipping_free_threshold')
                            ->label('Ücretsiz Kargo Limiti (Kuruş)')
                            ->numeric()
                            ->helperText('Örn: 1000 TL için 100000. Sepet bu tutarı geçerse kargo ücretsiz olur.')
                            ->required(), // Bu alanın zorunlu olmasını isteyebilirsin
                    ]),
            ])
            ->statePath('data'); // Formu $data değişkenine bağla
    }

    /**
     * "Değişiklikleri Kaydet" butonuna basıldığında
     */
    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key], 
                ['value' => $value ?? ''] // Değer boşsa null yerine boş string kaydet
            );
        }

        \Filament\Notifications\Notification::make()
            ->title('Ayarlar Kaydedildi')
            ->success()
            ->send();
    }
}