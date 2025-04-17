<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemIngredientResource\Pages;
use App\Models\MenuItemIngredient;
use App\Models\Menu;
use App\Models\Ingredient;
use App\Models\Restaurant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MenuItemIngredientResource extends Resource
{
    protected static ?string $model = MenuItemIngredient::class;

    protected static ?string $navigationGroup = 'Управление меню';
    protected static ?string $navigationIcon = 'heroicon-o-link';
    
    protected static ?string $navigationLabel = 'Ингредиенты блюд';
    
    protected static ?string $modelLabel = 'Ингредиент блюда';
    
    protected static ?string $pluralModelLabel = 'Ингредиенты блюд';

    public static function form(Form $form): Form
    {
        $userRestaurants = Restaurant::where('user_id', Auth::id())->pluck('id')->toArray();
        
        return $form
            ->schema([
                Forms\Components\Select::make('menu_item_id')
                    ->label('Блюдо')
                    ->options(function () use ($userRestaurants) {
                        if (Auth::user()->is_admin) {
                            return Menu::pluck('name', 'id');
                        }
                        return Menu::whereIn('restaurant_id', $userRestaurants)->pluck('name', 'id');
                    })
                    ->required()
                    ->searchable(),
                
                Forms\Components\Select::make('ingredient_id')
                    ->label('Ингредиент')
                    ->options(Ingredient::pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                
                Forms\Components\TextInput::make('quantity_required')
                    ->label('Требуемое количество')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('menuItem.name')
                    ->label('Блюдо')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('ingredient.name')
                    ->label('Ингредиент')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('quantity_required')
                    ->label('Требуемое количество')
                    ->numeric()
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
            'index' => Pages\ListMenuItemIngredients::route('/'),
            'create' => Pages\CreateMenuItemIngredient::route('/create'),
            'edit' => Pages\EditMenuItemIngredient::route('/{record}/edit'),
        ];
    }
    
    // Показывать только связи блюд ресторанов текущего пользователя (если не админ)
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        if (!auth()->user()->is_admin) {
            $userRestaurants = Restaurant::where('user_id', Auth::id())->pluck('id')->toArray();
            $query->whereHas('menuItem', function (Builder $query) use ($userRestaurants) {
                $query->whereIn('restaurant_id', $userRestaurants);
            });
        }
        
        return $query;
    }
}