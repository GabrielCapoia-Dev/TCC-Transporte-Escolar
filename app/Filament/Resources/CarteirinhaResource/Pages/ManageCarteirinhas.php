<?php

namespace App\Filament\Resources\CarteirinhaResource\Pages;

use App\Filament\Resources\CarteirinhaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCarteirinhas extends ManageRecords
{
    protected static string $resource = CarteirinhaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
