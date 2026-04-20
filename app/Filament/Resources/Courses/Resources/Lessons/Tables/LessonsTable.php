<?php

namespace App\Filament\Resources\Courses\Resources\Lessons\Tables;

use App\Models\Lesson;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class LessonsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('period')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('is_published')
                    ->boolean(),
                TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
                TernaryFilter::make('is_published')
                    ->label(__('Published')),
            ], layout: FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->recordActionsColumnLabel(__('Actions'))
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('moveUp')
                    ->hiddenLabel()
                    ->icon('heroicon-s-arrow-up')
                    ->color('success')
                    ->action(function (Lesson $record) {
                        moveUp($record->id, Lesson::class);
                    }),
                Action::make('moveDown')
                    ->hiddenLabel()
                    ->icon('heroicon-s-arrow-down')
                    ->color('success')
                    ->action(function (Lesson $record) {
                        moveDown($record->id, Lesson::class);
                    }),
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
