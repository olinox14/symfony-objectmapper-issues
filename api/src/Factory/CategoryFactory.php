<?php

namespace App\Factory;

use App\Entity\Category;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends ObjectFactory<Category>
 */
final class CategoryFactory extends PersistentObjectFactory
{
    public static function class(): string
    {
        return Category::class;
    }

    protected function defaults(): array
    {
        return [
            'tag' => self::faker()->word(),
        ];
    }
}
