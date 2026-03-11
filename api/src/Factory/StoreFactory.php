<?php

namespace App\Factory;

use App\Entity\Store;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends ObjectFactory<Store>
 */
final class StoreFactory extends PersistentObjectFactory
{
    public static function class(): string
    {
        return Store::class;
    }

    protected function defaults(): array
    {
        return [
            'title' => self::faker()->sentence(3),
            'siret' => self::faker()->numerify('##############'),
            'surface' => 100,
            'published' => true,
            'openingDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2024-01-01 00:00:00'),
            // relations left null/empty by default; set in stories when needed
        ];
    }
}
