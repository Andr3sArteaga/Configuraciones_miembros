<?php

namespace App\Filament\Admin\Resources\Equipos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EquipoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('nombre_equipo'),
                TextEntry::make('ubicacion'),
                TextEntry::make('cantidad_integrantes')
                    ->numeric(),
                TextEntry::make('id_lider_equipo'),
                TextEntry::make('estado'),
                TextEntry::make('creado')
                    ->dateTime(),
                TextEntry::make('actualizado')
                    ->dateTime(),
            ]);
    }
}
