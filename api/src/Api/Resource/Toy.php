<?php

declare(strict_types=1);

namespace App\Api\Resource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Entity\Toy as ToyEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[ApiResource(
    shortName: 'Toy',
    stateOptions: new Options(entityClass: ToyEntity::class)
)]
#[Get]
#[Map(source: ToyEntity::class)]
final class Toy
{
    #[ApiProperty(identifier: true)]
    public int $id;

    public string $description;

    protected ?float $price = null;
}
