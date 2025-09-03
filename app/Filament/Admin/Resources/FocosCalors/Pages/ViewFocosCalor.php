<?php

namespace App\Filament\Admin\Resources\FocosCalors\Pages;

use App\Filament\Admin\Resources\FocosCalors\FocosCalorResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFocosCalor extends ViewRecord
{
    protected static string $resource = FocosCalorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
