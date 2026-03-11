<?php

declare(strict_types=1);

namespace App\Api\Embedded\Store;

use ApiPlatform\Metadata\ApiProperty;
use App\Api\Embedded\Manager\Badge;
use App\Entity\Manager as ManagerEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: ManagerEntity::class)]
final class Manager
{
    public int $id;

    public string $name;

    public ?int $seniority = null;

    #[ApiProperty(genId: false)]
    public ?Badge $badge = null;
}
