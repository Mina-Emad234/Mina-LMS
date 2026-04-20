<?php

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
