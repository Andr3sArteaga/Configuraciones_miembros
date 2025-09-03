<?php

namespace App\Filament\Admin\Resources\FocosCalors\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class FocosCalorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('latitude')
                    ->required()
                    ->numeric(),
                TextInput::make('longitude')
                    ->required()
                    ->numeric(),
                TextInput::make('confidence')
                    ->numeric(),
                DatePicker::make('acq_date')
                    ->required(),
                TimePicker::make('acq_time')
                    ->required(),
                TextInput::make('bright_ti4')
                    ->numeric(),
                TextInput::make('bright_ti5')
                    ->numeric(),
                TextInput::make('frp')
                    ->numeric(),
                DateTimePicker::make('creado'),
            ]);
    }
}
