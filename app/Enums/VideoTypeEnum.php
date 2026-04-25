<?php

namespace App\Enums;

enum VideoTypeEnum: string
{
    case Youtube = 'youtube';
    case Vimeo = 'vimeo';

    public static function values(): array
    {
        return [
            self::Youtube->value,
            self::Vimeo->value,
        ];
    }
}
