<?php

namespace App\Factory;

use App\Entity\Toy;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends ObjectFactory<Toy>
 */
final class ToyFactory extends PersistentObjectFactory
{
    public static function class(): string
    {
        return Toy::class;
    }

    protected function defaults(): array
    {
        return [
            'description' => self::faker()->sentence(),
            'price' => 10,
            // store: left null by default; can be set explicitly in stories
        ];
    }
}
