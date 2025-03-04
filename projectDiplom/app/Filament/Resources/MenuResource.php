<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\MenuCategory;
use App\Models\Ingredient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;
    protected static ?string $navigationGroup = 'Управление меню';
    protected static ?string $navigationIcon = 'heroicon-o-cake';
    protected static ?string $navigationLabel = 'Меню';
    protected static ?string $navigationBadge = 'menu';
    protected static ?string $navigationBadgeColor = 'success';
    protected static ?string $navigationBadgeIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationBadgeIconColor = 'success';
    protected static ?string $navigationBadgeIconSize = 'lg';
    protected static ?string $modelLabel = 'Блюдо';
    protected static ?string $pluralModelLabel = 'Блюда';

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $isAdmin = $user->is_admin;
        
        // Получаем рестораны пользователя
        $userRestaurants = Restaurant::when(!$isAdmin, function ($query) use ($user) {
            return $query->where('user_id', $user->id);
        })->pluck('name', 'id')->toArray();
        
        // Определяем, нужно ли показывать выбор ресторана
        $hasMultipleRestaurants = count($userRestaurants) > 1;
        $showRestaurantField = $hasMultipleRestaurants || $isAdmin;
        
        // Получаем ID ресторана по умолчанию
        $defaultRestaurantId = null;
        if (!$showRestaurantField && count($userRestaurants) === 1) {
            $defaultRestaurantId = array_key_first($userRestaurants);
        }
        
        $schema = [];
        
        // Добавляем поле выбора ресторана, если нужно
        if ($showRestaurantField) {
            $schema[] = Forms\Components\Select::make('restaurant_id')
                ->label('Ресторан')
                ->options($userRestaurants)
                ->required()
                ->live()
                ->afterStateUpdated(fn (Set $set) => $set('category_id', null));
        } else {
            // Если у пользователя только один ресторан, добавляем скрытое поле
            $schema[] = Forms\Components\Hidden::make('restaurant_id')
                ->default($defaultRestaurantId);
        }
        
        // Добавляем основные поля формы
        $schema[] = Forms\Components\Select::make('category_id')
            ->label('Категория')
            ->options(function (Get $get) use ($userRestaurants, $defaultRestaurantId) {
                $restaurantId = $get('restaurant_id') ?: $defaultRestaurantId;
                
                if (!$restaurantId) {
                    return [];
                }
                
                return MenuCategory::where('restaurant_id', $restaurantId)->pluck('name', 'id');
            })
            ->required()
            ->searchable()
            ->preload();
            
        $schema[] = Forms\Components\TextInput::make('name')
            ->label('Название блюда')
            ->required()
            ->maxLength(255);
            
        $schema[] = Forms\Components\Textarea::make('description')
            ->label('Описание')
            ->maxLength(65535);
            
        $schema[] = Forms\Components\TextInput::make('price')
            ->label('Цена')
            ->required()
            ->numeric()
            ->prefix('₽');
            
        $schema[] = Forms\Components\FileUpload::make('image_url')
            ->label('Изображение')
            ->image()
            ->directory('menu-images')
            ->visibility('public');
            
        $schema[] = Forms\Components\Toggle::make('is_available')
            ->label('Доступно')
            ->default(true);
        
        return $form->schema($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Изображение'),
                Tables\Columns\TextColumn::make('restaurant.name')
                    ->label('Ресторан')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Категория')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Цена')
                    ->money('RUB')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_available')
                    ->label('Доступно'),
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
            // Пустой массив, без RelationManagers
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenu::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
    
    // Показывать только блюда ресторанов текущего пользователя (если не админ)
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
    
    // Метод для получения ID ресторана по умолчанию
    public static function getDefaultRestaurantId(): ?int
    {
        $user = Auth::user();
        
        if ($user->is_admin) {
            return null;
        }
        
        $userRestaurant = Restaurant::where('user_id', $user->id)->first();
        
        return $userRestaurant ? $userRestaurant->id : null;
    }
}
