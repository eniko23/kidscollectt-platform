<?php

namespace App\Filament\Resources\GalleryImageResource\Pages;

use App\Filament\Resources\GalleryImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditGalleryImage extends EditRecord
{
    protected static string $resource = GalleryImageResource::class;

    public ?string $originalImageUrl = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->originalImageUrl = $data['original_image_url'] ?? null;
        unset($data['original_image_url']);
        return $data;
    }

    protected function afterSave(): void
    {
        if (! empty($this->originalImageUrl)) {
            try {
                $this->record->addMediaFromUrl($this->originalImageUrl)
                    ->toMediaCollection('gallery-images');
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Resim İndirilemedi')
                    ->body('Resim linki hatalı veya sunucu erişemiyor: ' . $e->getMessage())
                    ->danger()
                    ->send();
            }
        }
    }
}
