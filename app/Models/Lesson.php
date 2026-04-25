<?php

namespace App\Models;

use App\Enums\VideoTypeEnum;
use App\Observers\ProgressObserver;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['title', 'slug', 'description', 'learnings', 'video_id', 'video_type', 'duration', 'is_published', 'order', 'course_id', 'course_section_id'])]
#[Appends(['period'])]
#[ObservedBy(ProgressObserver::class)]
class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted()
    {
        static::creating(function ($model) {
            $maxOrder = static::where('course_id', $model->course_id)->max('order') ?? 0;
            $model->order = $maxOrder + 1;
        });
    }

    protected $casts = [
        'is_published' => 'boolean',
        'order' => 'integer',
        'duration' => 'integer',
        'video_type' => VideoTypeEnum::class,
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    public function getPeriodAttribute()
    {
        if ($this->duration > 0) {
            return getPeriodFormat($this->duration);
        } else {
            return '00:00';
        }
    }

    public function isUserComplete($userId): bool
    {
        if (! $this->course->checkEnrolement($userId)) {
            return false;
        }

        if ($this->order === 1) {
            return true;
        }

        $maxCompletedOrder = $this->progress()
            ->where('enrolement_id', $this->course->enrolements()->where('user_id', $userId)->first()->id)
            ->latest()->first()->lesson->order ?? 0;

        return $this->order <= ($maxCompletedOrder + 1);
    }

    public function isAvailableForUser($userId): bool
    {
        if (! $this->course->checkEnrolement($userId)) {
            return false;
        }

        if ($this->order == 1) {
            return true;
        }

        $maxAvailableOrder = $this->course->lessons()->isPublished()->whereHas('progress', function ($query) use ($userId) {
            $query->where('enrolement_id', $this->course->enrolements()->where('user_id', $userId)->first()->id);
        })->latest()->first() ?? null;

        $nextLesson = $this->course->lessons()
            ->isPublished()
            ->where('order', '>', $maxAvailableOrder->order ?? 0)
            ->orderBy('order', 'asc')
            ->first();

        return $this->order <= $nextLesson->order;
    }

    public function markAsComplete($userId)
    {
        $this->progress()->updateOrCreate([
            'enrolement_id' => $this->course->enrolements()->where('user_id', $userId)->first()->id,
        ]);
    }

    public function scopeIsPublished($query)
    {
        return $query->where('is_published', true);
    }
}
