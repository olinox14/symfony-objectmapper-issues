<?php

declare(strict_types=1);

namespace App\Enum;

use App\Trait\EnumMethodsTrait;

enum ColorEnum: string
{
    use EnumMethodsTrait;

    case GREEN = 'GREEN';
    case VIOLET = 'VIOLET';
    case ORANGE = 'ORANGE';
    case GREY = 'GREY';
}
