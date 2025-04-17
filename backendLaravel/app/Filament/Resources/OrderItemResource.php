<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderItemResource\Pages;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Menu;
use App\Models\Restaurant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OrderItemResource extends Resource
{
    protected static ?string $model = OrderItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    
    protected static ?string $navigationLabel = 'Позиции заказов';
    
    protected static ?string $modelLabel = 'Позиция заказа';
    
    protected static ?string $pluralModelLabel = 'Позиции заказов';

    public static function form(Form $form): Form
    {
        $userRestaurants = Restaurant::where('user_id', Auth::id())->pluck('id')->toArray();
        
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->label('Заказ')
                    ->options(function () use ($userRestaurants) {
                        if (Auth::user()->is_admin) {
                            return Order::pluck('id', 'id');
                        }
                        return Order::whereHas('table', function (Builder $query) use ($userRestaurants) {
                            $query->whereIn('restaurant_id', $userRestaurants);
                        })->pluck('id', 'id');
                    })
                    ->required()
                    ->searchable(),
                
                Forms\Components\Select::make('menu_item_id')
                    ->label('Блюдо')
                    ->options(function () use ($userRestaurants) {
                        if (Auth::user()->is_admin) {
                            return Menu::pluck('name', 'id');
                        }
                        return Menu::whereIn('restaurant_id', $userRestaurants)->pluck('name', 'id');
                    })
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $price = Menu::find($state)->price ?? 0;
                            $set('price', $price);
                        }
                    }),
                
                Forms\Components\TextInput::make('quantity')
                    ->label('Количество')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->minValue(1),
                
                Forms\Components\TextInput::make('price')
                    ->label('Цена за единицу')
                    ->required()
                    ->numeric()
                    ->prefix('₽'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->label('№ заказа')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('menuItem.name')
                    ->label('Блюдо')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Количество')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('price')
                    ->label('Цена')
                    ->money('RUB')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOrderItems::route('/'),
            'create' => Pages\CreateOrderItem::route('/create'),
            'edit' => Pages\EditOrderItem::route('/{record}/edit'),
        ];
    }
    
    // Показывать только позиции заказов ресторанов текущего пользователя (если не админ)
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        if (!auth()->user()->is_admin) {
            $userRestaurants = Restaurant::where('user_id', Auth::id())->pluck('id')->toArray();
            $query->whereHas('order.table', function (Builder $query) use ($userRestaurants) {
                $query->whereIn('restaurant_id', $userRestaurants);
            });
        }
        
        return $query;
    }
}