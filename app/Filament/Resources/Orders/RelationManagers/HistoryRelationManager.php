<?php

namespace App\Filament\Resources\Orders\RelationManagers;

// --- GEREKLİ TÜM 'use' BİLDİRİMLERİ ---
// TÜM Bileşenler 'Forms\Components' altından gelmeli
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput; // Gerekirse diye ekledim

// Ama Fonksiyon imzası (form()) 'Schema' kullanır
use Filament\Schemas\Schema; 

// Diğer gerekli sınıflar
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;



class HistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'history';

    // Paneldeki başlığı Türkçeleştiriyoruz
    protected static ?string $modelLabel = 'Sipariş Geçmişi';
    protected static ?string $pluralModelLabel = 'Sipariş Geçmişi';

    // --- DOĞRU FONKSİYON İMZASI: (Schema $schema) ---
public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 'status' (Metin Kutusu) YERİNE AÇILIR MENÜ
                Select::make('status')
                    ->label('Sipariş Durumu')
                    ->options([
                        // Bu listeyi OrderForm'daki ile aynı tuttuk
                        'pending' => 'Onay Bekliyor',
                        'processing' => 'Hazırlanıyor',
                        'shipped' => 'Kargoya Verildi',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal Edildi',
                    ])
                    ->required(),
                
                Textarea::make('notes')
                    ->label('Notlar (Müşteri Görebilir)')
                    ->helperText('Bu not, sipariş geçmişinde müşteriye gösterilebilir.')
                    ->columnSpanFull(),
            ]);
    }

    // TABLO BÖLÜMÜ (Bu zaten doğruydu)
public function table(Table $table): Table
{
    return $table
        ->recordTitleAttribute('status')
        ->columns([
            TextColumn::make('status')
                ->label('Durum')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'warning',
                    'processing' => 'info',
                    'shipped' => 'primary',
                    'completed' => 'success',
                    'cancelled' => 'danger',
                    default => 'gray',
                })
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'pending' => 'Onay Bekliyor',
                    'processing' => 'Hazırlanıyor',
                    'shipped' => 'Kargoya Verildi',
                    'completed' => 'Tamamlandı',
                    'cancelled' => 'İptal Edildi',
                    default => $state,
                }),

            TextColumn::make('notes')
                ->label('Notlar'),

            TextColumn::make('created_at')
                ->label('Tarih')
                ->dateTime('d/m/Y H:i')
                ->sortable(),
        ])
        ->filters([
            //
        ])
        // --- EKSİK OLAN KISIM BURASIYDI ---
        ->headerActions([
            CreateAction::make(), // "Yeni Kayıt Ekle" butonu
        ])
        // --- BİTTİ ---
        ->recordActions([
            DeleteAction::make(),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
}
}