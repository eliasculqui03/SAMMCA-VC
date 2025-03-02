<?php

namespace App\Filament\Resources\MiembroResource\Pages;

use App\Filament\Resources\MiembroResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMiembro extends CreateRecord
{
    protected static string $resource = MiembroResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
