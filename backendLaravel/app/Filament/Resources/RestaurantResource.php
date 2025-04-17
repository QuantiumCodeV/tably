<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestaurantResource\Pages;
use App\Filament\Resources\RestaurantResource\RelationManagers;
use App\Models\Restaurant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class RestaurantResource extends Resource
{
    protected static ?string $model = Restaurant::class;

    protected static ?string $navigationGroup = 'Управление рестораном';
    protected static ?string $title = 'Рестораны';
    protected static ?string $slug = 'restaurants';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationBadge = 'restaurants';
    protected static ?string $navigationBadgeColor = 'success';
    protected static ?string $navigationBadgeIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationBadgeIconColor = 'success';
    protected static ?string $navigationBadgeIconSize = 'lg';

    protected static ?string $navigationLabel = 'Рестораны';

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $modelLabel = 'Ресторан';
    
    protected static ?string $pluralModelLabel = 'Рестораны';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
                
                Forms\Components\TextInput::make('name')
                    ->label('Название ресторана')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\FileUpload::make('logo_url')
                    ->label('Логотип')
                    ->image()
                    ->disk('public')
                    ->directory('restaurant-logos')
                    ->visibility('public')
                    ->imagePreviewHeight('250')
                    ->loadingIndicatorPosition('left')
                    ->panelAspectRatio('2:1')
                    ->panelLayout('integrated'),
                
                Forms\Components\TextInput::make('city')
                    ->label('Город')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('address')
                    ->label('Адрес')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Textarea::make('description')
                    ->label('Описание')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_url')
                    ->label('Логотип')
                    ->disk('public')
                    ->visibility('public')
                    ->height(40)
                    ->circular(false),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('city')
                    ->label('Город')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('address')
                    ->label('Адрес')
                    ->searchable(),
                
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
            'index' => Pages\ListRestaurants::route('/'),
            'create' => Pages\CreateRestaurant::route('/create'),
            'edit' => Pages\EditRestaurant::route('/{record}/edit'),
        ];
    }
    
    // Показывать только рестораны текущего пользователя (если не админ)
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        if (!auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }
        
        return $query;
    }
}
