<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryImageResource\Pages;
use App\Models\GalleryImage;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GalleryImageResource extends Resource
{
    protected static ?string $model = GalleryImage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;
    
    protected static ?string $navigationLabel = 'Galeri';
    protected static ?string $modelLabel = 'Galeri Görseli';
    protected static ?string $pluralModelLabel = 'Galeri Görselleri';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Görsel Başlığı / Dosya Adı')
                    ->helperText('Bu isim dosya adı olarak kullanılacak (örn: urun-1 → urun-1.jpg)')
                    ->required()
                    ->maxLength(255)
                    ->alphaDash(),

                TextInput::make('image_url')
                    ->label('Görsel Linki (URL)')
                    ->helperText('Harici bir linkten görsel eklemek için URL girin. Dosya indirilip public/uploads klasörüne kaydedilir.')
                    ->url()
                    ->columnSpanFull(),

                FileUpload::make('filename')
                    ->label('veya Dosya Yükle')
                    ->helperText('Bilgisayardan görsel yükleyin. Dosya public/uploads klasörüne kaydedilir.')
                    ->directory('')
                    ->disk('uploads')
                    ->image()
                    ->imageEditor()
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->preserveFilenames(false)
                    ->getUploadedFileNameForStorageUsing(function ($file, $get) {
                        $title = $get('title') ?? 'image';
                        $extension = $file->getClientOriginalExtension();
                        return $title . '.' . $extension;
                    })
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('filename')
                    ->label('Görsel')
                    ->disk('uploads')
                    ->width(80)
                    ->height(80),
                TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable(),
                TextColumn::make('filename')
                    ->label('Dosya Adı')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Yükleme Tarihi')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleryImages::route('/'),
            'create' => Pages\CreateGalleryImage::route('/create'),
            'edit' => Pages\EditGalleryImage::route('/{record}/edit'),
        ];
    }
}
