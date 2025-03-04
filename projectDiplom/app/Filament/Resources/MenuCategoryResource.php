<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuCategoryResource\Pages;
use App\Filament\Resources\MenuCategoryResource\RelationManagers;
use App\Models\MenuCategory;
use App\Models\Restaurant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class MenuCategoryResource extends Resource
{
    protected static ?string $model = MenuCategory::class;
    protected static ?string $navigationGroup = 'Управление меню';
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Категории меню';
    protected static ?string $navigationBadge = 'menu_categories';
    protected static ?string $navigationBadgeColor = 'success';
    protected static ?string $navigationBadgeIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationBadgeIconColor = 'success';
    protected static ?string $navigationBadgeIconSize = 'lg';
    protected static ?string $modelLabel = 'Категория меню';
    protected static ?string $pluralModelLabel = 'Категории меню';

    public static function form(Form $form): Form
    {
        $userRestaurants = Restaurant::where('user_id', Auth::id())->pluck('name', 'id');
        $hasMultipleRestaurants = $userRestaurants->count() > 1;
        
        return $form
            ->schema([
                Forms\Components\Select::make('restaurant_id')
                    ->label('Ресторан')
                    ->options($userRestaurants)
                    ->required()
                    ->visible($hasMultipleRestaurants || Auth::user()->is_admin),
                Forms\Components\TextInput::make('name')
                    ->label('Название категории')
                    ->required()
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создана')
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
            'index' => Pages\ListMenuCategories::route('/'),
            'create' => Pages\CreateMenuCategory::route('/create'),
            'edit' => Pages\EditMenuCategory::route('/{record}/edit'),
        ];
    }
    
    // Показывать только категории ресторанов текущего пользователя (если не админ)
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
