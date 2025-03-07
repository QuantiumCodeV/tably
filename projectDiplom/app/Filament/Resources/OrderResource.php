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

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('id')->label('Order ID'),
                TextEntry::make('table.restaurant.name')->label('Restaurant'),
                TextEntry::make('table.table_number')->label('Table Number'),
                TextEntry::make('total_price')->label('Total Price')->money('RUB'),
                TextEntry::make('status')->label('Status'),
                TextEntry::make('created_at')->label('Created At')->dateTime(),
                TextEntry::make('updated_at')->label('Updated At')->dateTime(),
                Repeater::make('items')
                    ->label('Order Items')
                    ->schema([
                        TextEntry::make('dish.name')->label('Dish Name'),
                        TextEntry::make('quantity')->label('Quantity'),
                        TextEntry::make('price')->label('Price')->money('RUB'),
                    ])
                    ->columns(3),
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