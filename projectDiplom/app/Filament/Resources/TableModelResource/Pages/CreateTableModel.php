<?php

namespace App\Filament\Resources\TableModelResource\Pages;

use App\Filament\Resources\TableModelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTableModel extends CreateRecord
{
    protected static string $resource = TableModelResource::class;
}
