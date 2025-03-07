<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TableResource\Pages;
use App\Filament\Resources\TableResource\RelationManagers;
use App\Models\Restaurant;
use App\Models\Table as TableModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Filament\Infolists\Components\ImageEntry;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Support\Facades\FilamentView;

class TableResource extends Resource
{
    protected static ?string $model = TableModel::class;

    protected static ?string $navigationGroup = 'Управление рестораном';
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';
    
    protected static ?string $navigationLabel = 'Столы';
    
    protected static ?string $modelLabel = 'Стол';
    
    protected static ?string $pluralModelLabel = 'Столы';

    public static function form(Form $form): Form
    {
        $userRestaurants = Restaurant::where('user_id', Auth::id())->pluck('name', 'id');
        $hasMultipleRestaurants = $userRestaurants->count() > 1;
        
        return $form
            ->schema([
                Forms\Components\Select::make('restaurant_id')
                    ->label('Ресторан')
                    ->options($userRestaurants)
                    ->required(),
                
                Forms\Components\TextInput::make('table_number')
                    ->label('Номер стола')
                    ->required()
                    ->integer(),
                
                Forms\Components\TextInput::make('capacity')
                    ->label('Вместимость')
                    ->required()
                    ->integer()
                    ->minValue(1),
                
                Forms\Components\Hidden::make('qr_code_url')
                    ->default(function () {
                        // Генерация уникального URL для QR-кода
                        return 'qr_' . Str::random(20);
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('restaurant.name')
                    ->label('Ресторан')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('table_number')
                    ->label('Номер стола')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('capacity')
                    ->label('Вместимость')
                    ->sortable(),
                
                Tables\Columns\ImageColumn::make('qr_code_url')
                    ->label('QR-код')
                    ->height(50)
                    ->circular(false)
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
                Tables\Actions\Action::make('downloadQrCode')
                    ->label('Скачать QR-код')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (TableModel $record): string => route('qrcode.generate', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('Информация о столе')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('restaurant.name')
                            ->label('Ресторан'),
                        \Filament\Infolists\Components\TextEntry::make('restaurant.city')
                            ->label('Город'),
                        \Filament\Infolists\Components\TextEntry::make('restaurant.address')
                            ->label('Адрес'),
                        \Filament\Infolists\Components\TextEntry::make('table_number')
                            ->label('Номер стола'),
                        \Filament\Infolists\Components\TextEntry::make('capacity') 
                            ->label('Вместимость'),
                        \Filament\Infolists\Components\TextEntry::make('created_at')
                            ->label('Дата создания')
                            ->dateTime('d.m.Y H:i'),
                    ])
                    ->columns(2),

                \Filament\Infolists\Components\Section::make('QR-код')
                    ->schema([
                        \Filament\Infolists\Components\ImageEntry::make('qr_code_url')
                            ->label('QR-код')
                            ->height(200)
                            ->extraImgAttributes(['class' => 'rounded']),
                            
                        \Filament\Infolists\Components\TextEntry::make('custom_link')
                            ->label('Ссылка для клиентов')
                            ->state(function (TableModel $record): string {
                                return config('app.frontend_url') . '/table/' . $record->id;
                            })
                            ->url(function (TableModel $record): string {
                                return config('app.frontend_url') . '/table/' . $record->id;
                            })
                            ->openUrlInNewTab()
                            ->copyable()
                            ->weight(FontWeight::Bold),

                        \Filament\Infolists\Components\TextEntry::make('download_qr')
                            ->label('Скачать QR-код')
                            ->state('Скачать')
                            ->url(function (TableModel $record): string {
                                return route('qrcode.generate', $record);
                            })
                            ->openUrlInNewTab()
                            ->badge()
                            ->color('success'),
                    ])
                    ->collapsible(),
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
            'index' => Pages\ListTables::route('/'),
            'create' => Pages\CreateTable::route('/create'),
            'view' => Pages\ViewTable::route('/{record}'),
            'edit' => Pages\EditTable::route('/{record}/edit'),
        ];
    }
    
    // Показывать только столы ресторанов текущего пользователя (если не админ)
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        if (!auth()->user()->is_admin) {
            $query->whereHas('restaurant', function (Builder $query) {
                $query->where('user_id', auth()->id());
            });
        }
        
        return $query;
    }
}
