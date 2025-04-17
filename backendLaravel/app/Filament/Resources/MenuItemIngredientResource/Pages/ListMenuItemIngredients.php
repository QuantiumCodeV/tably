<?php

namespace App\Filament\Resources\MenuItemIngredientResource\Pages;

use App\Filament\Resources\MenuItemIngredientResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMenuItemIngredients extends ListRecords
{
    protected static string $resource = MenuItemIngredientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
