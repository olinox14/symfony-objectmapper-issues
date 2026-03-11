<?php

namespace App\Factory;

use App\Entity\Badge;
use App\Enum\ColorEnum;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends ObjectFactory<Badge>
 */
final class BadgeFactory extends PersistentObjectFactory
{
    public static function class(): string
    {
        return Badge::class;
    }

    protected function defaults(): array
    {
        return [
            'label' => self::faker()->word(),
            'color' => self::faker()->randomElement(ColorEnum::cases()),
            'isActive' => true,
        ];
    }
}
