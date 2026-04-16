<?php

declare(strict_types=1);

namespace App\Api\Resource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Manager as ManagerEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[ApiResource(
    shortName: 'Manager',
    stateOptions: new Options(entityClass: ManagerEntity::class)
)]
#[Map(source: ManagerEntity::class)]
final class Manager
{
    #[ApiProperty(identifier: true)]
    public int $id;

    public string $name;

    public ?int $seniority = null;
}
