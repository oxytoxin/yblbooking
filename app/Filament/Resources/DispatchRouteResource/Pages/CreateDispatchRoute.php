<?php

namespace App\Filament\Resources\DispatchRouteResource\Pages;

use App\Filament\Resources\DispatchRouteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDispatchRoute extends CreateRecord
{
    protected static string $resource = DispatchRouteResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
