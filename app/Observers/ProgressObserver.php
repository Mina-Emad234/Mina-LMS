<?php

namespace App\Observers;

use App\Models\Progress;

class ProgressObserver
{
    /**
     * Handle the Progress "created" event.
     */
    public function created(Progress $progress): void
    {
        $progress->enrolement->update([
            'is_completed' => ($progress->enrolement->progress()->count() >= $progress->enrolement->course->lessons()->isPublished()->count()) && $progress->lesson->course->getProgressPercentage($progress->enrolement->user_id) === 100,
            'completed_at' => ($progress->enrolement->progress()->count() >= $progress->enrolement->course->lessons()->isPublished()->count()) && $progress->lesson->course->getProgressPercentage($progress->enrolement->user_id) === 100 ? now() : null,
        ]);
    }

    /**
     * Handle the Progress "updated" event.
     */
    public function updated(Progress $progress): void
    {
        //
    }

    /**
     * Handle the Progress "deleted" event.
     */
    public function deleted(Progress $progress): void
    {
        //
    }

    /**
     * Handle the Progress "restored" event.
     */
    public function restored(Progress $progress): void
    {
        //
    }

    /**
     * Handle the Progress "force deleted" event.
     */
    public function forceDeleted(Progress $progress): void
    {
        //
    }
}
