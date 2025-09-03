<?php

namespace App\Filament\Admin\Resources\Equipos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EquiposTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('nombre_equipo')
                    ->searchable(),
                TextColumn::make('ubicacion'),
                TextColumn::make('cantidad_integrantes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('id_lider_equipo'),
                TextColumn::make('estado')
                    ->searchable(),
                TextColumn::make('creado')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('actualizado')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
