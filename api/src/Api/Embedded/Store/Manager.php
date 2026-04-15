<?php

declare(strict_types=1);

namespace App\Api\Embedded\Store;

use App\Entity\Manager as ManagerEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: ManagerEntity::class)]
final class Manager
{
    public int $id;

    public string $name;

    public ?int $seniority = null;
}
