<?php

namespace App\Filament\Resources\GalleryImageResource\Pages;

use App\Filament\Resources\GalleryImageResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class CreateGalleryImage extends CreateRecord
{
    protected static string $resource = GalleryImageResource::class;

    public ?string $imageUrl = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->imageUrl = $data['image_url'] ?? null;
        unset($data['image_url']);
        return $data;
    }

    protected function afterCreate(): void
    {
        // Link ile ekleme varsa ve dosya yüklenmemişse
        if (!empty($this->imageUrl) && empty($this->record->filename)) {
            try {
                // URL'den dosya uzantısını al
                $urlPath = parse_url($this->imageUrl, PHP_URL_PATH);
                $extension = pathinfo($urlPath, PATHINFO_EXTENSION) ?: 'jpg';
                
                // Geçerli uzantı kontrolü
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                if (!in_array(strtolower($extension), $allowedExtensions)) {
                    $extension = 'jpg';
                }

                $filename = $this->record->title . '.' . $extension;
                $savePath = public_path('uploads/' . $filename);

                // Dosyayı indir
                $response = Http::timeout(30)->get($this->imageUrl);
                
                if ($response->successful()) {
                    File::put($savePath, $response->body());
                    
                    // Veritabanını güncelle
                    $this->record->update(['filename' => $filename]);
                    
                    Notification::make()
                        ->title('Görsel başarıyla indirildi!')
                        ->success()
                        ->send();
                } else {
                    throw new \Exception('HTTP hatası: ' . $response->status());
                }
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Görsel indirilemedi')
                    ->body('Hata: ' . $e->getMessage())
                    ->danger()
                    ->send();
            }
        }
    }
}
