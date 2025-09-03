<?php

namespace App\Filament\Admin\Resources\Equipos\Pages;

use App\Filament\Admin\Resources\Equipos\EquipoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEquipos extends ListRecords
{
    protected static string $resource = EquipoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
