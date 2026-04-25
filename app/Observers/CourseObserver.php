<?php

namespace App\Observers;

use App\Models\CourseRating;

class CourseObserver
{
    /**
     * Handle the Course "created" event.
     */
    public function created(CourseRating $courseRating): void
    {
        $this->savedRating($courseRating);
    }

    /**
     * Handle the Course "updated" event.
     */
    public function updated(CourseRating $courseRating): void
    {
        $this->savedRating($courseRating);
    }

    /**
     * Handle the Course "deleted" event.
     */
    public function deleted(CourseRating $courseRating): void
    {
        $this->savedRating($courseRating);
    }

    /**
     * Handle the Course "restored" event.
     */
    public function restored(CourseRating $courseRating): void
    {
        $this->savedRating($courseRating);
    }

    /**
     * Handle the Course "force deleted" event.
     */
    public function forceDeleted(CourseRating $courseRating): void
    {
        $this->savedRating($courseRating);
    }

    /**
     * Handle the Course "saved" event.
     */
    public function savedRating(CourseRating $courseRating): void
    {
        $ratings = $courseRating->course->ratings()->sum('rating');
        $count = $courseRating->course->ratings()->count();
        $courseRating->course->update([
            'rating' => $count > 0 ? $ratings / $count : 0,
            'review_count' => $count,
        ]);
    }
}
