<?php

namespace App\Filament\Admin\Resources\Equipos\Pages;

use App\Filament\Admin\Resources\Equipos\EquipoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEquipo extends ViewRecord
{
    protected static string $resource = EquipoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
