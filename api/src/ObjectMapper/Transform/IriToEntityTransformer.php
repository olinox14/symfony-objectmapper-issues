<?php
declare(strict_types=1);

namespace App\ObjectMapper\Transform;

use ApiPlatform\Metadata\IriConverterInterface;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

final class IriToEntityTransformer implements TransformCallableInterface
{
    public function __construct(
        private readonly IriConverterInterface $iriConverter
    ) {}

    public function __invoke(mixed $value, object $source, ?object $target): mixed
    {
        if (null === $value || !is_string($value)) {
            return null;
        }

        return $this->iriConverter->getResourceFromIri($value);
    }
}
