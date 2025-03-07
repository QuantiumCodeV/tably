<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Restaurant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Repeater;
use Filament\Infolists\Infolist;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    
    protected static ?string $navigationLabel = 'Заказы';
    
    protected static ?string $modelLabel = 'Заказ';
    
    protected static ?string $pluralModelLabel = 'Заказы';

    public static function form(Form $form): Form
    {
        $userRestaurants = Restaurant::where('user_id', Auth::id())->pluck('id')->toArray();
        
        return $form
            ->schema([
                Forms\Components\Select::make('table_id')
                    ->label('Стол')
                    ->options(function () use ($userRestaurants) {
                        if (Auth::user()->is_admin) {
                            return \App\Models\Table::with('restaurant')
                                ->get()
                                ->mapWithKeys(function ($table) {
                                    return [$table->id => "Стол №{$table->table_number} ({$table->restaurant->name})"];
                                });
                        }
                        return Table::whereIn('restaurant_id', $userRestaurants)
                            ->with('restaurant')
                            ->get()
                            ->mapWithKeys(function ($table) {
                                return [$table->id => "Стол №{$table->table_number} ({$table->restaurant->name})"];
                            });
                    })
                    ->required()
                    ->searchable(),
                
                Forms\Components\TextInput::make('total_price')
                    ->label('Общая стоимость')
                    ->required()
                    ->numeric()
                    ->prefix('₽'),
                
                Forms\Components\Select::make('status')
                    ->label('Статус')
                    ->options([
                        'в обработке' => 'В обработке',
                        'готово' => 'Готово',
                        'оплачено' => 'Оплачено',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('№ заказа')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('table.restaurant.name')
                    ->label('Ресторан')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('table.table_number')
                    ->label('№ стола')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Сумма')
                    ->money('RUB')
                    ->sortable(),
                
                Tables\Columns\SelectColumn::make('status')
                    ->label('Статус')
                    ->options([
                        'в обработке' => 'В обработке',
                        'готово' => 'Готово',
                        'оплачено' => 'Оплачено',
                    ])
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'в обработке' => 'В обработке',
                        'готово' => 'Готово',
                        'оплачено' => 'Оплачено',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
                \Filament\Infolists\Components\Section::make('Информация о заказе')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('id')
                            ->label('ID заказа'),
                        \Filament\Infolists\Components\TextEntry::make('table.restaurant.name')
                            ->label('Ресторан'),
                        \Filament\Infolists\Components\TextEntry::make('table.table_number')
                            ->label('Номер стола'),
                        \Filament\Infolists\Components\TextEntry::make('total_price')
                            ->label('Итоговая сумма')
                            ->money('RUB'),
                        \Filament\Infolists\Components\TextEntry::make('status')
                            ->label('Статус'),
                        \Filament\Infolists\Components\TextEntry::make('created_at')
                            ->label('Создан')
                            ->dateTime('d.m.Y H:i'),
                        \Filament\Infolists\Components\TextEntry::make('updated_at')
                            ->label('Обновлен')
                            ->dateTime('d.m.Y H:i'),
                    ])
                    ->columns(2),

                \Filament\Infolists\Components\Section::make('Позиции заказа')
                    ->schema([
                        \Filament\Infolists\Components\RepeatableEntry::make('orderItems')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('menuItem.name')
                                    ->label('Название блюда'),
                                \Filament\Infolists\Components\TextEntry::make('quantity')
                                    ->label('Количество'),
                                \Filament\Infolists\Components\TextEntry::make('price')
                                    ->label('Цена')
                                    ->money('RUB'),
                            ])
                            ->columns(3)
                    ])
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
    
    // Показывать только заказы ресторанов текущего пользователя (если не админ)
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        if (!auth()->user()->is_admin) {
            $userRestaurants = Restaurant::where('user_id', Auth::id())->pluck('id')->toArray();
            $query->whereHas('table', function (Builder $query) use ($userRestaurants) {
                $query->whereIn('restaurant_id', $userRestaurants);
            });
        }
        
        return $query;
    }
}