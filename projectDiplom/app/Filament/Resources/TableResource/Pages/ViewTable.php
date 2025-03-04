<?php

namespace App\Filament\Resources\TableResource\Pages;

use App\Filament\Resources\TableResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTable extends ViewRecord
{
    protected static string $resource = TableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            Actions\Action::make('qrCode')
                ->label('QR-код')
                ->icon('heroicon-o-qr-code')
                ->url(fn () => route('table.qrcode', $this->record))
                ->openUrlInNewTab(),
        ];
    }
} 