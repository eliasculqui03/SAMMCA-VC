<?php

namespace App\Filament\Resources\ConductividadResource\Pages;

use App\Filament\Resources\ConductividadResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageConductividads extends ManageRecords
{
    protected static string $resource = ConductividadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
