<?php

namespace App\Filament\Resources\TableResource\Pages;

use App\Filament\Resources\TableResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTable extends EditRecord
{
    protected static string $resource = TableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\Action::make('qrCode')
                ->label('QR-код')
                ->icon('heroicon-o-qr-code')
                ->url(fn () => route('table.qrcode', $this->record))
                ->openUrlInNewTab(),
        ];
    }
}
