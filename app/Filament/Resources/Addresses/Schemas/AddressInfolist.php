<?php

namespace App\Filament\Resources\Addresses\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AddressInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.id')
                    ->label('User'),
                TextEntry::make('label')
                    ->placeholder('-'),
                TextEntry::make('first_name'),
                TextEntry::make('last_name'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('address_line_1'),
                TextEntry::make('address_line_2')
                    ->placeholder('-'),
                TextEntry::make('district'),
                TextEntry::make('city'),
                TextEntry::make('country'),
                IconEntry::make('is_default_shipping')
                    ->boolean(),
                IconEntry::make('is_default_billing')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
