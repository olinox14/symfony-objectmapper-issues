<?php

declare(strict_types=1);

namespace App\Api\Dto\Store;

use App\Entity\Store as StoreEntity;
use App\ObjectMapper\Transform\IriToEntityTransformer;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: StoreEntity::class)]
final class UpdateStore
{
    #[Assert\Length(min: 1)]
    public ?string $title;

    #[Assert\Length(exactly: 14)]
    public ?string $siret;

    #[Assert\GreaterThanOrEqual(0)]
    public ?int $surface;

    public ?bool $published;

    #[Map(transform: IriToEntityTransformer::class)]
    public ?string $manager = null;

    public array $toys = [];
}
