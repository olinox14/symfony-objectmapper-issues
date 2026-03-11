<?php
declare(strict_types=1);

namespace App\Api\Dto\Store;

use App\Entity\Store as StoreEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: StoreEntity::class)]
final class CreateStore
{
    #[Assert\NotBlank]
    public string $title;

    #[Assert\Length(exactly: 14)]
    public ?string $siret = null;

    #[Assert\GreaterThanOrEqual(0)]
    public ?int $surface = null;

    public ?bool $published = null;

    public ?\DateTimeInterface $openingDate = null;
}
