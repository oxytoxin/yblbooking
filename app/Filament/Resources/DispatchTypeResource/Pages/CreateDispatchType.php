<?php

namespace App\Filament\Resources\DispatchTypeResource\Pages;

use App\Filament\Resources\DispatchTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDispatchType extends CreateRecord
{
    protected static string $resource = DispatchTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
