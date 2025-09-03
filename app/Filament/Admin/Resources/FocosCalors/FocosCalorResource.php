<?php

namespace App\Filament\Admin\Resources\FocosCalors;

use App\Filament\Admin\Resources\FocosCalors\Pages\CreateFocosCalor;
use App\Filament\Admin\Resources\FocosCalors\Pages\EditFocosCalor;
use App\Filament\Admin\Resources\FocosCalors\Pages\ListFocosCalors;
use App\Filament\Admin\Resources\FocosCalors\Pages\ViewFocosCalor;
use App\Filament\Admin\Resources\FocosCalors\Schemas\FocosCalorForm;
use App\Filament\Admin\Resources\FocosCalors\Schemas\FocosCalorInfolist;
use App\Filament\Admin\Resources\FocosCalors\Tables\FocosCalorsTable;
use App\Models\FocosCalor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FocosCalorResource extends Resource
{
    protected static ?string $model = FocosCalor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return FocosCalorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FocosCalorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FocosCalorsTable::configure($table);
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
            'index' => ListFocosCalors::route('/'),
            'create' => CreateFocosCalor::route('/create'),
            'view' => ViewFocosCalor::route('/{record}'),
            'edit' => EditFocosCalor::route('/{record}/edit'),
        ];
    }
}
