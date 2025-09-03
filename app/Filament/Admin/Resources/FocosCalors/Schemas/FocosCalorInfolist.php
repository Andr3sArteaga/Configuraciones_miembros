<?php

namespace App\Filament\Admin\Resources\FocosCalors\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FocosCalorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('latitude')
                    ->numeric(),
                TextEntry::make('longitude')
                    ->numeric(),
                TextEntry::make('confidence')
                    ->numeric(),
                TextEntry::make('acq_date')
                    ->date(),
                TextEntry::make('acq_time')
                    ->time(),
                TextEntry::make('bright_ti4')
                    ->numeric(),
                TextEntry::make('bright_ti5')
                    ->numeric(),
                TextEntry::make('frp')
                    ->numeric(),
                TextEntry::make('creado')
                    ->dateTime(),
            ]);
    }
}
