<?php

namespace App\Filament\Resources\RotasTransporteResource\Pages;

use App\Filament\Resources\RotasTransporteResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRotasTransportes extends ManageRecords
{
    protected static string $resource = RotasTransporteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
