<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Filament\Resources\Courses\CourseResource;
use App\Filament\Resources\Courses\Pages\ViewCourse;
use App\Filament\Tables\Columns\RatingColumn;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class RatingsRelationManager extends RelationManager
{
    protected static string $relationship = 'ratings';

    protected static ?string $relatedResource = CourseResource::class;

    protected static ?string $title = 'Ratings';

    public function isReadOnly(): bool
    {
        return true;
    }

    public static function canViewForRecord($ownerRecord, $pageClass): bool
    {
        return $pageClass === ViewCourse::class;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                RatingColumn::make('rating'),
            ])
            ->filters([
                TrashedFilter::make(),
            ], layout: FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->recordActions([

            ]);
    }
}
