<?php

use App\Enums\VideoTypeEnum;

if (! function_exists('getPeriodFormat')) {
    /**
     * Get Period Format.
     *
     * @return string
     */
    function getPeriodFormat($minutes)
    {
        if ($minutes < 60) {
            return "{$minutes} ".__('Minutes');
        } else {
            $hours = floor($minutes / 60);
            $remainingMinutes = $minutes % 60;

            $duration = $hours.' '.__('Hour');

            if ($remainingMinutes > 0) {
                $duration .= ' '.__('and')." {$remainingMinutes} ".__('Minutes');
            }

            return $duration;
        }

    }

}
if (! function_exists('moveUp')) {
    /**
     * Move Up.
     *
     * @return void
     */
    function moveUp($id, $model)
    {
        $record = $model::find($id);
        $previous = $model::where('course_id', $record->course_id)->where('order', '<', $record->order)
            ->orderByDesc('order')
            ->first();

        if ($previous) {
            [$record->order, $previous->order] = [$previous->order, $record->order];
            $record->save();
            $previous->save();
        }
    }
}
if (! function_exists('moveDown')) {
    /**
     * Move Down.
     *
     * @return void
     */
    function moveDown($id, $model)
    {
        $record = $model::find($id);
        $next = $model::where('course_id', $record->course_id)->where('order', '>', $record->order)
            ->orderBy('order')
            ->first();

        if ($next) {
            [$record->order, $next->order] = [$next->order, $record->order];
            $record->save();
            $next->save();
        }
    }
}

if (! function_exists('getVideoUrl')) {
    /**
     * Get video URL from ID and type.
     *
     * @param  string|null  $videoId
     * @param  string|null  $videoType
     * @return string|null
     */
    function getVideoUrl(?string $videoId, VideoTypeEnum|string|null $videoType): ?string
    {
        if (empty($videoId)) {
            return null;
        }

        $type = $videoType instanceof VideoTypeEnum ? $videoType->value : $videoType;
    
    return match ($type) {
            VideoTypeEnum::Youtube->value => 'https://www.youtube.com/embed/'.$videoId.'?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1',
            VideoTypeEnum::Vimeo->value => 'https://player.vimeo.com/video/'.$videoId.'?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media',
            default => null,
        };
    }
}

if (! function_exists('getAdminVideoUrl')) {
    /**
     * Get video URL from ID and type.
     *
     * @param  string|null  $videoId
     * @param  string|null  $videoType
     * @return string|null
     */
    function getAdminVideoUrl(?string $videoId, VideoTypeEnum|string|null $videoType): ?string
    {
        if (empty($videoId)) {
            return null;
        }

        $type = $videoType instanceof VideoTypeEnum ? $videoType->value : $videoType;

        return match ($type) {
            VideoTypeEnum::Youtube->value => 'https://www.youtube.com/embed/'.$videoId,
            VideoTypeEnum::Vimeo->value => 'https://player.vimeo.com/video/'.$videoId,
            default => null,
        };
    }
}