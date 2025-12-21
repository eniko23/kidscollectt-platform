<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\GalleryImage;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\File;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    public ?string $originalImageUrl = null;
    public ?string $galleryImageId = null;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->originalImageUrl = $data['original_image_url'] ?? null;
        $this->galleryImageId = $data['from_gallery_id'] ?? null;
        
        unset($data['original_image_url']);
        unset($data['from_gallery_id']);
        
        return $data;
    }

    protected function afterSave(): void
    {
        // 1. Manuel Link Kontrolü
        if (! empty($this->originalImageUrl)) {
            try {
                $this->record->addMediaFromUrl($this->originalImageUrl)
                    ->toMediaCollection('product-images');
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Resim İndirilemedi')
                    ->body('Resim linki hatalı veya sunucu erişemiyor: ' . $e->getMessage())
                    ->danger()
                    ->send();
            }
        }
        
        // 2. Galeri Seçimi Kontrolü
        if (! empty($this->galleryImageId)) {
            $galleryImage = GalleryImage::find($this->galleryImageId);
            if ($galleryImage && $galleryImage->filename) {
                try {
                    // Galeri dosyasının tam path'ini al
                    $sourcePath = public_path('uploads/' . $galleryImage->filename);
                    
                    if (File::exists($sourcePath)) {
                        // Galeri dosyasının URL'ini oluştur ve ürüne ekle
                        $url = asset('uploads/' . $galleryImage->filename);
                        $this->record->addMediaFromUrl($url)
                            ->toMediaCollection('product-images');
                            
                        Notification::make()
                            ->title('Galeri görseli eklendi')
                            ->success()
                            ->send();
                    }
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Galeri Görseli Kopyalanamadı')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            }
        }
    }
}
