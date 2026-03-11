<?php

declare(strict_types=1);

namespace App\ObjectMapper\Transform;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

final class PhoneTransformer implements TransformCallableInterface
{
    public function __invoke(mixed $value, object $source, ?object $target): ?string
    {
        if (!$value instanceof PhoneNumber) {
            return null;
        }

        return PhoneNumberUtil::getInstance()->format($value, PhoneNumberFormat::E164);
    }
}
