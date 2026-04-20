<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Filament\Resources\Courses\CourseResource;
use App\Filament\Resources\Courses\Pages\ViewCourse;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EnrolementsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrolements';

    protected static ?string $relatedResource = CourseResource::class;

    protected static ?string $title = 'Enrolements';

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
                TextColumn::make('enroled_at')
                    ->sortable()
                    ->dateTime(),
                IconColumn::make('is_completed')
                    ->boolean(),
                TextColumn::make('completed_at')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                TrashedFilter::make(),
                TernaryFilter::make('is_completed')
                    ->label('Completed')
                    ->native(false),
            ], layout: FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->recordActions([
                // CreateAction::make(),
            ]);
    }
}
