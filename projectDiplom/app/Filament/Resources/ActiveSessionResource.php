<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActiveSessionResource\Pages;
use App\Models\ActiveSession;

use App\Models\Restaurant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ActiveSessionResource extends Resource
{
    protected static ?string $model = ActiveSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    
    protected static ?string $navigationGroup = 'Админ-панель';
    protected static ?string $navigationLabel = 'Активные сессии';
    
    protected static ?string $modelLabel = 'Активная сессия';
    
    protected static ?string $pluralModelLabel = 'Активные сессии';

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
                
                Forms\Components\TextInput::make('session_token')
                    ->label('Токен сессии')
                    ->default(fn () => Str::random(32))
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\DateTimePicker::make('started_at')
                    ->label('Время начала')
                    ->default(now())
                    ->required(),
                
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('Время окончания')
                    ->default(now()->addHours(2))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('table.restaurant.name')
                    ->label('Ресторан')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('table.table_number')
                    ->label('№ стола')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('session_token')
                    ->label('Токен сессии')
                    ->searchable()
                    ->limit(16),
                
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Начало')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Окончание')
                    ->dateTime('d.m.Y H:i')
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
            'index' => Pages\ListActiveSessions::route('/'),
            'create' => Pages\CreateActiveSession::route('/create'),
            'edit' => Pages\EditActiveSession::route('/{record}/edit'),
        ];
    }
    
    // Показывать только сессии столов ресторанов текущего пользователя (если не админ)
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