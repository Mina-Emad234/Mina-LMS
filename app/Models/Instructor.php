<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable(['name', 'title', 'bio', 'linkedin_url', 'phone', 'email'])]
#[Appends(['profile'])]
class Instructor extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    public function getProfileAttribute(): ?string
    {
        if ($this->media()->first() != null) {
            return $this->getFirstMediaUrl('instructors');
        } else {
            return INSTRUCTOR_IMAGE_PATH;
        }
    }

    public function course()
    {
        return $this->hasMany(Course::class);
    }
}
