<?php
declare(strict_types=1);

namespace App\Api\Dto\Store;

use App\Entity\Store as StoreEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: StoreEntity::class)]
final class StoreCollectionItem
{
    public int $id;

    public string $title;

    public ?bool $published = null;
}

