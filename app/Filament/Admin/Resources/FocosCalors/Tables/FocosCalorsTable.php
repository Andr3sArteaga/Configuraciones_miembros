<?php

namespace App\Filament\Admin\Resources\FocosCalors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FocosCalorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('latitude')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('longitude')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('confidence')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('acq_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('acq_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('bright_ti4')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bright_ti5')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('frp')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('creado')
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
