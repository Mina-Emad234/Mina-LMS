<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Filament\Infolists\Components\RatingEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CourseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('target_audience')
                    ->placeholder('-')
                    ->columnSpanFull(),
                RatingEntry::make('rating'),
                TextEntry::make('review_count')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('is_featured')
                    ->boolean(),
                TextEntry::make('category.name')
                    ->numeric(),
                TextEntry::make('level.name')
                    ->numeric(),
                TextEntry::make('instructor.name')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                ImageEntry::make('image')
                    ->label('Profile Picture')
                    ->disk('public'),
                RepeatableEntry::make('sections')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('order'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
