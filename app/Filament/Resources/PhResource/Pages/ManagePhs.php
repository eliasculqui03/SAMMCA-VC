<?php

namespace App\Filament\Resources\PhResource\Pages;

use App\Filament\Resources\PhResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePhs extends ManageRecords
{
    protected static string $resource = PhResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
