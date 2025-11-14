<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox; // Bülten için
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hesap Bilgileri')
                    ->description('Müşterinin ana hesap ve giriş bilgileri.')
                    ->schema([
                        TextInput::make('first_name')
                            ->label('Adı')
                            ->required(),

                        TextInput::make('last_name')
                            ->label('Soyadı')
                            ->required(),
                        
                        TextInput::make('email')
                            ->label('E-posta Adresi')
                            ->email()
                            ->required()
                            ->unique(table: 'users', column: 'email', ignoreRecord: true), // Kendisi hariç benzersiz
                        
                        TextInput::make('phone')
                            ->label('Telefon Numarası')
                            ->tel(), 
                    ])->columns(2), // Bu bölüm 2 sütunlu olsun

                Section::make('Parola')
                    ->description('Yeni müşteri için bir parola belirleyin.')
                    ->schema([
                        TextInput::make('password')
                            ->label('Parola')
                            ->password() // Yazarken gizler (*****)
                            ->required(fn (string $operation): bool => $operation === 'create') // SADECE "Oluştur" sayfasında zorunlu
                            ->visibleOn('create') // SADECE "Oluştur" sayfasında görünür
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state)) // Kaydederken şifreler (Hash::make)
                            ->confirmed(), // 'password_confirmation' ile eşleşmeli

                        TextInput::make('password_confirmation')
                            ->label('Parola (Tekrar)')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->visibleOn('create'), // SADECE "Oluştur" sayfasında görünür
                    ])->columns(2),

                Section::make('Bayi & Müşteri Grubu Ayarları')
                    ->description('Müşterinin tipini ve onay durumunu yönetin.')
                    ->schema([
                        // 'user_type' (Metin Kutusu) YERİNE AÇILIR MENÜ
                        Select::make('user_type')
                            ->label('Müşteri Grubu')
                            ->options([
                                'bireysel' => 'Bireysel Müşteri',
                                'bayi' => 'Bayi',
                            ])
                            ->default('bireysel')
                            ->required(),

                        // 'is_approved' (Admin Onayı)
                        Toggle::make('is_approved')
                            ->label('Bayi Hesabı Onaylandı')
                            ->helperText('Eğer müşteri grubu "Bayi" ise, hesabı aktifleştirmek için bunu açın.'),

                        // 'subscribed_to_newsletter' (Bülten)
                        Checkbox::make('subscribed_to_newsletter') // Toggle yerine Checkbox daha mantıklı
                            ->label('Bülten Aboneliği Var'),
                    ])->columns(3), // Bu bölüm 3 sütunlu olsun

                Section::make('Bayi Firma Bilgileri')
                    ->description('Sadece "Bayi" tipi müşteriler için geçerlidir.')
                    ->schema([
                        TextInput::make('company_name')
                            ->label('Firma Adı'),
                        
                        TextInput::make('tax_office')
                            ->label('Vergi Dairesi'),

                        TextInput::make('tax_id')
                            ->label('Vergi Numarası (VKN / TCKN)'),
                    ])->columns(3), // Bu bölüm 3 sütunlu olsun
            ]);
    }
}
