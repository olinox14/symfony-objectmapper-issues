<?php

namespace App\Factory;

use App\Entity\Supplier;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends ObjectFactory<Supplier>
 */
final class SupplierFactory extends PersistentObjectFactory
{
    public static function class(): string
    {
        return Supplier::class;
    }

    protected function defaults(): array
    {
        return [
            'name' => self::faker()->company(),
        ];
    }
}
