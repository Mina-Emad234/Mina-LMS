<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserTypeEnum;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'phone'])]
#[Hidden(['password', 'remember_token'])]
#[Appends(['profile'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'type' => UserTypeEnum::class,
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->type == UserTypeEnum::ADMIN->value;
    }

    public function getProfileAttribute(): ?string
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name);
    }

    public function checkUserCourse($courseId)
    {
        return $this->enrolements()->where('course_id', $courseId)->exists();
    }

    public function enrolements()
    {
        return $this->hasMany(Enrolement::class);
    }

    public function progress()
    {
        return $this->hasManyThrough(Progress::class, Enrolement::class);
    }
}
