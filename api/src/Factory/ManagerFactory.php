<?php

namespace App\Factory;

use App\Entity\Manager;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Manager>
 */
final class ManagerFactory extends PersistentObjectFactory
{
    public static function class(): string
    {
        return Manager::class;
    }

    protected function defaults(): array
    {
        return [
            'name' => self::faker()->name(),
            'seniority' => 5,
            // badge: left null by default; can be set explicitly in stories
        ];
    }
}
