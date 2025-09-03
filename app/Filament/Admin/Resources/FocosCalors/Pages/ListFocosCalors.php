<?php

namespace App\Filament\Admin\Resources\FocosCalors\Pages;

use App\Filament\Admin\Resources\FocosCalors\FocosCalorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFocosCalors extends ListRecords
{
    protected static string $resource = FocosCalorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
