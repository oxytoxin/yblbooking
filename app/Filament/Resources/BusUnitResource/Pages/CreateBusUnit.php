<?php

namespace App\Filament\Resources\BusUnitResource\Pages;

use App\Filament\Resources\BusUnitResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBusUnit extends CreateRecord
{
    protected static string $resource = BusUnitResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
