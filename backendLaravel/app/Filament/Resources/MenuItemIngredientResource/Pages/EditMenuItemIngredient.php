<?php

namespace App\Filament\Resources\MenuItemIngredientResource\Pages;

use App\Filament\Resources\MenuItemIngredientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenuItemIngredient extends EditRecord
{
    protected static string $resource = MenuItemIngredientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
