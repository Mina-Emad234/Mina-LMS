<?php

namespace App\Filament\Resources\Courses\Tables;

use App\Filament\Tables\Columns\RatingColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                RatingColumn::make('rating')
                    ->sortable(),
                TextColumn::make('review_count')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->boolean(),
                TextColumn::make('category.name')
                    ->sortable(),
                TextColumn::make('level.name')
                    ->sortable(),
                TextColumn::make('instructor.name')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
                TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->native(false),
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->searchable()
                    ->preload()
                    ->relationship('category', 'name')
                    ->native(false),
                SelectFilter::make('level_id')
                    ->searchable()
                    ->preload()
                    ->label('Level')
                    ->relationship('level', 'name')
                    ->native(false),
                SelectFilter::make('instructor_id')
                    ->searchable()
                    ->preload()
                    ->label('Instructor')
                    ->relationship('instructor', 'name')
                    ->native(false),
            ], layout: FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->recordActionsColumnLabel(__('Actions'))
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
