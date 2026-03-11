<?php
declare(strict_types=1);

namespace App\Api\Embedded\Manager;

use App\Entity\Badge as BadgeEntity;
use App\Enum\ColorEnum;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(target: BadgeEntity::class, source: BadgeEntity::class)]
final class Badge
{
    public int $id;

    public string $label;

    public ColorEnum $color;

    public bool $isActive;
}
