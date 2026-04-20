<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CourseLevelEnum: string implements HasLabel
{
    case BEGINNER = 'beginner';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';
    case EXPERT = 'expert';

    public function getLabel(): string
    {
        return match ($this) {
            self::BEGINNER => 'Beginner',
            self::INTERMEDIATE => 'Intermediate',
            self::ADVANCED => 'Advanced',
            self::EXPERT => 'Expert',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::BEGINNER => 'success',
            self::INTERMEDIATE => 'warning',
            self::ADVANCED => 'orange',
            self::EXPERT => 'danger',
        };
    }
}
