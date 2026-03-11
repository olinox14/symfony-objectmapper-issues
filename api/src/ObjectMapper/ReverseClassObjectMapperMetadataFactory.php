<?php

declare(strict_types=1);

namespace App\ObjectMapper;

use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\ObjectMapper\Metadata\Mapping;
use Symfony\Component\ObjectMapper\Metadata\ObjectMapperMetadataFactoryInterface;
use Symfony\Component\ObjectMapper\Transform\MapCollection;

/**
 * Maps classes based on attributes found on the target's properties.
 *
 * Temporary backport from Symfony 8.1.
 *
 * @see https://github.com/symfony/symfony/blob/8.1/src/Symfony/Component/ObjectMapper/Metadata/ReverseClassObjectMapperMetadataFactory.php
 */
final class ReverseClassObjectMapperMetadataFactory implements ObjectMapperMetadataFactoryInterface
{
    /**
     * @var array<string, list<Mapping>>
     */
    private array $attributesCache = [];

    /**
     * @param array<class-string, class-string>      $classMap              Entity → Embedded sub-object class
     * @param array<class-string, list<string>>       $collectionProperties  Entity class → property names needing MapCollection (forward)
     * @param array<class-string, list<string>>       $skipProperties        Resource class → property names to skip (reverse)
     */
    public function __construct(
        private readonly ObjectMapperMetadataFactoryInterface $objectMapperMetadataFactory,
        private readonly array $classMap = [],
        private readonly array $collectionProperties = [],
        private readonly array $skipProperties = [],
    ) {
    }

    public function create(object $object, ?string $property = null, array $context = []): array
    {
        $class = $object::class;

        $isForward = isset($this->classMap[$class]);
        $key = ($isForward ? 'fwd:' : 'rev:').$class.($property ? '.'.$property : '');

        if (isset($this->attributesCache[$key])) {
            return $this->attributesCache[$key];
        }

        $mappings = $this->objectMapperMetadataFactory->create($object, $property, $context);

        // Forward: entity Collection → resource array via MapCollection
        if ($property && isset($this->collectionProperties[$class]) && \in_array($property, $this->collectionProperties[$class], true)) {
            $mappings[] = new Mapping(transform: MapCollection::class);

            return $this->attributesCache[$key] = $mappings;
        }

        // Reverse: skip collection properties when mapping resource → entity
        if ($property && isset($this->skipProperties[$class]) && \in_array($property, $this->skipProperties[$class], true)) {
            return $this->attributesCache[$key] = [new Mapping(if: false)];
        }

        if (!$targetClass = $this->classMap[$class] ?? null) {
            return $mappings;
        }

        if (!$property) {
            $mappings[] = new Mapping($targetClass);

            return $this->attributesCache[$key] = $mappings;
        }

        $refl = new \ReflectionClass($targetClass);
        foreach ($refl->getProperties() as $reflProperty) {
            $attributes = $reflProperty->getAttributes(Map::class, \ReflectionAttribute::IS_INSTANCEOF);

            foreach ($attributes as $attribute) {
                $map = $attribute->newInstance();
                if (!$map->source) {
                    continue;
                }

                // Extract root property from expressions like "contact?.email" or "contact.email"
                $sourceRoot = explode('.', str_replace('?.', '.', $map->source))[0];

                if ($sourceRoot !== $property) {
                    continue;
                }

                $mappings[] = new Mapping($reflProperty->getName(), $map->source, $map->if, $map->transform);
            }
        }

        return $this->attributesCache[$key] = $mappings;
    }
}
