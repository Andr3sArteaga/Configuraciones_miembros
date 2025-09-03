<?php

namespace App\Filament\Admin\Resources\Equipos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EquipoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre_equipo')
                    ->required(),
                TextInput::make('ubicacion'),
                TextInput::make('cantidad_integrantes')
                    ->numeric()
                    ->default(0),
                TextInput::make('id_lider_equipo'),
                TextInput::make('estado')
                    ->default('ACTIVO'),
                DateTimePicker::make('creado'),
                DateTimePicker::make('actualizado'),
            ]);
    }
}
