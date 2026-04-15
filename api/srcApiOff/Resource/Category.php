<?php

declare(strict_types=1);

namespace App\Api\Resource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Category as CategoryEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[ApiResource(
    shortName: 'Category',
    stateOptions: new Options(entityClass: CategoryEntity::class)
)]
#[Map(source: CategoryEntity::class)]
final class Category
{
    #[ApiProperty(identifier: true)]
    public int $id;

    public string $tag;
}
