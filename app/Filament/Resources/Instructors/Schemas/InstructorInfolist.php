<?php

namespace App\Filament\Resources\Instructors\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InstructorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('title'),
                TextEntry::make('bio')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('linkedin_url')
                    ->label('LinkedIn Profile')
                    ->icon('icon-linked-in')
                    ->url(fn ($record) => $record->linkedin_url, true)
                    ->placeholder('-'),
                TextEntry::make('phone'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                ImageEntry::make('profile')
                    ->label('Profile Picture')
                    ->disk('public')
                    ->state(function ($record) {
                        return $record->profile;
                    })
                    ->circular(),
            ]);
    }
}
