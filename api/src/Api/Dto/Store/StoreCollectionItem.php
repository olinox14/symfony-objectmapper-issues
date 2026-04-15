<?php
declare(strict_types=1);

namespace App\Api\Dto\Store;

use ApiPlatform\Metadata\ApiProperty;
use App\Api\Embedded\Store\Category;
use App\Api\Embedded\Store\Manager;
use App\Entity\Store as StoreEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: StoreEntity::class)]
final class StoreCollectionItem
{
    public int $id;

    public string $title;

    public ?bool $published = null;

    #[ApiProperty(genId: false)]
    public ?Manager $manager = null;

    /** @var Category[] */
    #[ApiProperty(genId: false)]
    public array $categories = [];
}

