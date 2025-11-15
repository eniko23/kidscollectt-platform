<x-filament-panels::page>
    <form wire:submit.prevent="save">
        {{-- Formu buraya render et --}}
        {{ $this->form }}

        {{-- Kaydet Butonu --}}
        <div class_alias="mt-6">
            <x-filament::button type="submit">
                Değişiklikleri Kaydet
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>