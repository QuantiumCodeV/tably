<?php

namespace App\Filament\Resources\TableModelResource\Pages;

use App\Filament\Resources\TableModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTableModels extends ListRecords
{
    protected static string $resource = TableModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
