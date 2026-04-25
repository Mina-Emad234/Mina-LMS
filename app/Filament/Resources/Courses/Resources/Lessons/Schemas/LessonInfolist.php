<?php

namespace App\Filament\Resources\Courses\Resources\Lessons\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;

class LessonInfolist
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
                TextEntry::make('learnings')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('period')
                    ->placeholder('-'),
                IconEntry::make('is_published')
                    ->boolean(),
                TextEntry::make('order')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                MediaAction::make('video_url')
                    ->hiddenLabel()
                    ->media(fn ($record) => $record->video_url)
                    ->url(fn ($record) => getAdminVideoUrl($record->video_id, $record->video_type))
                    ->tooltip('Watch Video')
                    ->color('info')
                    ->icon('heroicon-o-video-camera')
            ]);
    }
}
