<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    })
                    ->maxLength(255)
                    ->required(),
                TextInput::make('slug')
                    ->disabled(function ($record) {
                        return $record !== null;
                    })
                    ->unique(ignoreRecord: true)
                    ->required(),
                Textarea::make('description')
                    ->maxLength(2000)
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('target_audience')
                    ->maxLength(2000)
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_featured')
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Select::make('level_id')
                    ->relationship('level', 'name')
                    ->required(),
                Select::make('instructor_id')
                    ->relationship('instructor', 'name')
                    ->required(),
                SpatieMediaLibraryFileUpload::make('image')
                    ->disk('public')
                    ->openable()
                    ->maxSize(1024 * 1024 * 20)
                    ->downloadable()
                    ->acceptedFileTypes(['image/*'])
                    ->collection('courses')
                    ->translateLabel(),
                Repeater::make('sections')
                    ->relationship('sections')
                    ->live()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('order')
                            ->numeric()
                            ->readOnly()
                            ->default(function (Get $get) {
                                $sections = $get('../../sections');

                                return is_array($sections) ? count($sections) : 1;
                            }),
                    ])
                    ->orderColumn('order')
                    ->reorderable(false)
                    ->defaultItems(1)
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }
}
