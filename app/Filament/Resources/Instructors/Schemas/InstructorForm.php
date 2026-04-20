<?php

namespace App\Filament\Resources\Instructors\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InstructorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('title')
                    ->maxLength(255)
                    ->required(),
                Textarea::make('bio')
                    ->required()
                    ->maxLength(2000)
                    ->columnSpanFull(),
                TextInput::make('linkedin_url')
                    ->label('LinkedIn Profile')
                    ->nullable()
                    ->maxLength(1000)
                    ->url(),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(20)
                    ->rules(['phone'])
                    ->unique('instructors', 'phone', ignoreRecord: true)
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->unique('instructors', 'email', ignoreRecord: true)
                    ->maxLength(255)
                    ->required(),
                SpatieMediaLibraryFileUpload::make('profile')
                    ->disk('public')
                    ->openable()
                    ->maxSize(1024 * 1024 * 20)
                    ->downloadable()
                    ->acceptedFileTypes(['image/*'])
                    ->collection('instructors')
                    ->translateLabel(),
            ]);
    }
}
