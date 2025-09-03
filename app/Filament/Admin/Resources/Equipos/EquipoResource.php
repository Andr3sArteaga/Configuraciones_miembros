<?php

namespace App\Filament\Admin\Resources\Equipos;

use App\Filament\Admin\Resources\Equipos\Pages\CreateEquipo;
use App\Filament\Admin\Resources\Equipos\Pages\EditEquipo;
use App\Filament\Admin\Resources\Equipos\Pages\ListEquipos;
use App\Filament\Admin\Resources\Equipos\Pages\ViewEquipo;
use App\Filament\Admin\Resources\Equipos\Schemas\EquipoForm;
use App\Filament\Admin\Resources\Equipos\Schemas\EquipoInfolist;
use App\Filament\Admin\Resources\Equipos\Tables\EquiposTable;
use App\Models\Equipo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EquipoResource extends Resource
{
    protected static ?string $model = Equipo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Equipo';

    public static function form(Schema $schema): Schema
    {
        return EquipoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EquipoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EquiposTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEquipos::route('/'),
            'create' => CreateEquipo::route('/create'),
            'view' => ViewEquipo::route('/{record}'),
            'edit' => EditEquipo::route('/{record}/edit'),
        ];
    }
}
