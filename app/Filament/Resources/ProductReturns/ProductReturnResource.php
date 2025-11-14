<?php

namespace App\Filament\Resources\ProductReturns;

use App\Filament\Resources\ProductReturns\Pages\CreateProductReturn;
use App\Filament\Resources\ProductReturns\Pages\EditProductReturn;
use App\Filament\Resources\ProductReturns\Pages\ListProductReturns;
use App\Filament\Resources\ProductReturns\Pages\ViewProductReturn;
use App\Filament\Resources\ProductReturns\Schemas\ProductReturnForm;
use App\Filament\Resources\ProductReturns\Schemas\ProductReturnInfolist;
use App\Filament\Resources\ProductReturns\Tables\ProductReturnsTable;
use App\Models\ProductReturn;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section; // Formu gruplamak için

class ProductReturnResource extends Resource
{
    protected static ?string $model = ProductReturn::class;
    protected static ?string $navigationLabel = 'İade Talepleri';
    protected static ?string $modelLabel = 'İade Talebi';
    protected static ?string $pluralModelLabel = 'İade Talepleri';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return ProductReturnForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductReturnInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductReturnsTable::configure($table);
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
            'index' => ListProductReturns::route('/'),
            'create' => CreateProductReturn::route('/create'),
            'view' => ViewProductReturn::route('/{record}'),
            'edit' => EditProductReturn::route('/{record}/edit'),
        ];
    }
}
