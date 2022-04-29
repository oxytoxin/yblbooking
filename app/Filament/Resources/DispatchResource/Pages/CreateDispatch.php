<?php

namespace App\Filament\Resources\DispatchResource\Pages;

use App\Filament\Resources\DispatchResource;
use App\Models\DispatchType;
use Filament\Resources\Pages\CreateRecord;

class CreateDispatch extends CreateRecord
{
    protected static string $resource = DispatchResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function create(bool $another = false): void
    {
        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        $this->callHook('afterValidate');

        $stops = DispatchType::find($data['dispatch_type_id'])->stops;
        if (count($this->data['available_dispatch_routes']) != $stops) {
            $this->notify('error', "Available routes should be {$stops}");
            return;
        }

        $data = $this->mutateFormDataBeforeCreate($data);


        $this->callHook('beforeCreate');

        $this->record = $this->handleRecordCreation($data);

        $this->form->model($this->record)->saveRelationships();

        $this->callHook('afterCreate');

        if (filled($this->getCreatedNotificationMessage())) {
            $this->notify(
                'success',
                $this->getCreatedNotificationMessage(),
            );
        }

        if ($another) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->record::class);
            $this->record = null;

            $this->fillForm();

            return;
        }

        $this->redirect($this->getRedirectUrl());
    }
}
