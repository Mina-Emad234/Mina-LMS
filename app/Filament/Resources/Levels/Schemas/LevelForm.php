<?php

namespace App\Filament\Resources\Levels\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LevelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->maxLength(50)
                    ->unique('levels', 'name', ignoreRecord: true)
                    ->required(),
                ColorPicker::make('color')
                    ->required(),
            ]);
    }
}
