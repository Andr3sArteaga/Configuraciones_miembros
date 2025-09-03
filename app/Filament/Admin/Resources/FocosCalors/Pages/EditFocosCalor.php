<?php

namespace App\Filament\Admin\Resources\FocosCalors\Pages;

use App\Filament\Admin\Resources\FocosCalors\FocosCalorResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFocosCalor extends EditRecord
{
    protected static string $resource = FocosCalorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
