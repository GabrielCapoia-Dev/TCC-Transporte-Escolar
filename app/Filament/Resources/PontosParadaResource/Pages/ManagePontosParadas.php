<?php

namespace App\Filament\Resources\PontosParadaResource\Pages;

use App\Filament\Resources\PontosParadaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePontosParadas extends ManageRecords
{
    protected static string $resource = PontosParadaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
