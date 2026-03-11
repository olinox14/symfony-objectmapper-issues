<?php

declare(strict_types=1);

namespace App\Api\Embedded\Store;

use App\Entity\Category as CategoryEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: CategoryEntity::class)]
final class Category
{
    public int $id;

    public string $tag;
}
