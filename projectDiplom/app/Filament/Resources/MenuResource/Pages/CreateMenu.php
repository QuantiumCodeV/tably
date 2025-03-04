<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\Select;

class CreateMenu extends CreateRecord
{
    protected static string $resource = MenuResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
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
    
    protected function fillForm(): void
    {
        parent::fillForm();
        
        // Если у пользователя только один ресторан, автоматически выбираем его
        $defaultRestaurantId = MenuResource::getDefaultRestaurantId();
        
        if ($defaultRestaurantId) {
            $this->form->fill([
                'restaurant_id' => $defaultRestaurantId,
            ]);
        }
    }
}
