<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplyResource\Pages;
use App\Filament\Resources\SupplyResource\RelationManagers;
use App\Models\Supply;
use App\Models\Ingredient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplyResource extends Resource
{
    protected static ?string $model = Supply::class;

    protected static ?string $navigationGroup = 'Управление меню';
    protected static ?string $title = 'Поставки';
    protected static ?string $slug = 'supplies';
    protected static ?string $recordTitleAttribute = 'supply_date';
    protected static ?string $navigationBadge = 'supplies';
    protected static ?string $navigationBadgeColor = 'success';
    protected static ?string $navigationBadgeIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationBadgeIconColor = 'success';
    protected static ?string $navigationBadgeIconSize = 'lg';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Поставки';

    protected static ?string $modelLabel = 'Поставка';
    
    protected static ?string $pluralModelLabel = 'Поставки';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('ingredient_id')
                    ->label('Ингредиент')
                    ->options(Ingredient::pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                
                Forms\Components\TextInput::make('quantity_added')
                    ->label('Количество')
                    ->required()
                    ->numeric(),
                
                Forms\Components\DatePicker::make('supply_date')
                    ->label('Дата поставки')
                    ->required()
                    ->default(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ingredient.name')
                    ->label('Ингредиент')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('quantity_added')
                    ->label('Количество')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('ingredient.unit')
                    ->label('Единица измерения'),
                
                Tables\Columns\TextColumn::make('supply_date')
                    ->label('Дата поставки')
                    ->date('d.m.Y')
                    ->sortable(),
                
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
            'index' => Pages\ListSupplies::route('/'),
            'create' => Pages\CreateSupply::route('/create'),
            'edit' => Pages\EditSupply::route('/{record}/edit'),
        ];
    }
}
