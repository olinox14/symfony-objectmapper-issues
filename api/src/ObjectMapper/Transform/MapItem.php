<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\ObjectMapper\Transform;

use Symfony\Component\ObjectMapper\Exception\MappingException;
use Symfony\Component\ObjectMapper\ObjectMapper;
use Symfony\Component\ObjectMapper\ObjectMapperAwareInterface;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

/**
 * @template T of object
 *
 * @implements TransformCallableInterface<object, T>
 */
class MapItem implements TransformCallableInterface, ObjectMapperAwareInterface
{
    public function __construct(
        private ?ObjectMapperInterface $objectMapper = null,
        public readonly ?string $targetClass = null,
    ) {
    }

    public function withObjectMapper(ObjectMapperInterface $objectMapper): static
    {
        $clone = clone $this;
        $clone->objectMapper = $objectMapper;

        return $clone;
    }

    public function __invoke(mixed $value, object $source, ?object $target): mixed
    {
        $objectMapper = $this->objectMapper ??= new ObjectMapper();

        return $objectMapper->map($value, $this->targetClass);
    }
}
