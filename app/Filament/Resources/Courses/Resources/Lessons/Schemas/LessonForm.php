<?php

namespace App\Filament\Resources\Courses\Resources\Lessons\Schemas;

use App\Enums\VideoTypeEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class LessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    })
                    ->required(),
                TextInput::make('slug')
                    ->disabled(function ($record) {
                        return $record !== null;
                    })
                    ->unique(ignoreRecord: true)
                    ->required(),
                Textarea::make('description')
                    ->maxLength(2000)
                    ->columnSpanFull(),
                Textarea::make('learnings')
                    ->maxLength(2000)
                    ->columnSpanFull(),
                TextInput::make('video_id')
                    ->maxLength(2000)
                    ->required(),
                Select::make('video_type')
                    ->options(VideoTypeEnum::class)
                    ->required(),
                TextInput::make('duration')
                    ->maxLength(255)
                    ->label(__('Duration (in minutes)'))
                    ->required(),
                Toggle::make('is_published')
                    ->required(),
            ]);
    }
}
