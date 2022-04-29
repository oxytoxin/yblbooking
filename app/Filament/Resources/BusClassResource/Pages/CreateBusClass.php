<?php

namespace App\Filament\Resources\BusClassResource\Pages;

use App\Filament\Resources\BusClassResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBusClass extends CreateRecord
{
    protected static string $resource = BusClassResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
