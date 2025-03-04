<?php

namespace App\Filament\Resources\TableModelResource\Pages;

use App\Filament\Resources\TableModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTableModel extends EditRecord
{
    protected static string $resource = TableModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
