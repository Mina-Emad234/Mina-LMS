<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable(['title', 'slug', 'description', 'target_audience', 'rating', 'review_count', 'is_featured', 'category_id', 'level_id', 'instructor_id'])]
#[Appends(['image', 'total_lessons', 'period', 'enrolements_count'])]
class Course extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $casts = [
        'is_featured' => 'boolean',
        'rating' => 'decimal:1',
        'review_count' => 'integer',
    ];

    public function getImageAttribute(): ?string
    {
        if ($this->media()->first() != null) {
            return $this->getFirstMediaUrl('courses');
        } else {
            return COURSE_IMAGE_PATH;
        }
    }

    public function getTotalLessonsAttribute()
    {
        return $this->lessons()->isPublished()->count();
    }

    public function getEnrolementsCountAttribute()
    {
        return $this->enrolements()->count();
    }

    public function checkLessonProgress($userId, $lessonId)
    {
        return $this->lessons()->where('id', $lessonId)->whereHas('progress', function ($query) use ($userId) {
            $query->where('enrolement_id', $this->enrolements()->where('user_id', $userId)->first()->id);
        })->exists();
    }

    public function getProgressPercentage($userId)
    {
        if ($this->total_lessons == 0) {
            return 0;
        }

        return $this->lessons()->isPublished()->whereHas('progress', function ($query) use ($userId) {
            $query->where('enrolement_id', $this->enrolements()->where('user_id', $userId)->first()->id);
        })->count() / $this->total_lessons * 100;
    }

    public function getProgressPeriod($userId)
    {
        if ($this->isEnrolled($userId)) {
            $duration = $this->lessons()->isPublished()->whereHas('progress', function ($query) use ($userId) {
                $query->where('enrolement_id', $this->enrolements()->where('user_id', $userId)->first()->id);
            })->sum('duration');

            return getPeriodFormat($duration);
        } else {
            return ' - ';
        }
    }

    public function resumeLearning($userId)
    {
        $userprogress = $this->progress()->where('enrolement_id', $this->enrolements()->where('user_id', $userId)->first()->id);
        if (! $userprogress->exists()) {
            return $this->lessons()->isPublished()->first()->slug;
        } else {
            return $userprogress->orderBy('created_at', 'desc')->first()->lesson->slug;
        }
    }

    public function checkEnrolement($userId)
    {
        return $this->enrolements()->where('user_id', $userId)->exists();
    }

    public function checkRating($userId)
    {
        return $this->ratings()->where('user_id', $userId)->exists();
    }

    public function isCompleted($userId)
    {
        return $this->enrolements()->where('user_id', $userId)->where('is_completed', true)->exists();
    }

    public function getUserProgress($userId)
    {
        return $this->progress()->where('user_id', $userId)->get();
    }

    public function progress()
    {
        return $this->hasManyThrough(Progress::class, Lesson::class);
    }

    public function getProgressCount($userId)
    {
        return $this->lessons()->isPublished()->whereHas('progress', function ($query) use ($userId) {
            $query->where('enrolement_id', $this->enrolements()->where('user_id', $userId)->first()->id);
        })->count().'/'.$this->total_lessons;
    }

    public function isEnrolled($userId)
    {
        return $this->enrolements()->where('user_id', $userId)->exists();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function sections()
    {
        return $this->hasMany(CourseSection::class)->orderBy('order');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function ratings()
    {
        return $this->hasMany(CourseRating::class);
    }

    public function enrolements()
    {
        return $this->hasMany(Enrolement::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getDurationAttribute()
    {
        return $this->lessons()->isPublished()->sum('duration');
    }

    public function getPeriodAttribute()
    {
        if ($this->duration > 0) {
            return getPeriodFormat($this->duration);
        } else {
            return '00:00';
        }
    }
}
