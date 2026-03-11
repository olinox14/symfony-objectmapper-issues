<?php

declare(strict_types=1);

namespace App\Trait;

trait EnumMethodsTrait
{
    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return string[]
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function fromValue(string $value): self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        throw new \ValueError(sprintf('%s is not a valid backing value for enum %s', $value, self::class));
    }
}
