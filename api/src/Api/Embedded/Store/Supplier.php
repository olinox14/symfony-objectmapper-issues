<?php

declare(strict_types=1);

namespace App\Api\Embedded\Store;

use App\Entity\Supplier as SupplierEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: SupplierEntity::class)]
final class Supplier
{
    public int $id;

    public string $name;
}
