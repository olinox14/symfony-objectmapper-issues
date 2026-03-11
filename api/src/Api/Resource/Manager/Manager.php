<?php

declare(strict_types=1);

namespace App\Api\Resource\Manager;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Manager as ManagerEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[ApiResource(
    shortName: 'Manager',
    stateOptions: new Options(entityClass: ManagerEntity::class),
)]
#[Get]
#[GetCollection(
    uriTemplate: '/managers',
    normalizationContext: [
        'attributes' => ['id', 'name'],
    ],
)]
#[Map(source: ManagerEntity::class)]
class Manager
{
    #[ApiProperty(identifier: true)]
    public int $id;

    public string $name;
}
