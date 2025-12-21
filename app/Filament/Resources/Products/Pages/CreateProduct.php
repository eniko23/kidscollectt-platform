<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    public ?string $originalImageUrl = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->originalImageUrl = $data['original_image_url'] ?? null;
        unset($data['original_image_url']);
        return $data;
    }

    protected function afterCreate(): void
    {
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
    }
}
