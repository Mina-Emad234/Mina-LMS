<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserTypeEnum: string implements HasLabel
{
    case ADMIN = 'admin';
    case STUDENT = 'student';

    public function getLabel(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::STUDENT => 'Student',
        };
    }
}
