<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Если ресторан не выбран, но у пользователя только один ресторан
        if (empty($data['restaurant_id'])) {
            $defaultRestaurantId = MenuResource::getDefaultRestaurantId();
            if ($defaultRestaurantId) {
                $data['restaurant_id'] = $defaultRestaurantId;
            }
        }
        
        return $data;
    }
}
