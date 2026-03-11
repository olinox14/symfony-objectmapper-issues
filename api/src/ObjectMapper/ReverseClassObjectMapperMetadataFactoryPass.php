<?php

declare(strict_types=1);

namespace App\ObjectMapper;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\ObjectMapper\Attribute\Map;

/**
 * Auto-discovers the classMap and collectionProperties for ReverseClassObjectMapperMetadataFactory
 * by scanning resource and embedded classes for #[Map] attributes.
 */
final class ReverseClassObjectMapperMetadataFactoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(ReverseClassObjectMapperMetadataFactory::class)) {
            return;
        }

        $classMap = [];
        $collectionProperties = [];
        $skipProperties = [];

        // Scan Embedded classes for #[Map(source: ...)] → classMap
        $embeddedDir = \dirname(__DIR__).'/Api/Embedded';
        foreach (glob($embeddedDir.'/**/*.php') as $file) {
            $subDir = basename(\dirname($file));
            $class = 'App\\Api\\Embedded\\'.$subDir.'\\'.pathinfo($file, \PATHINFO_FILENAME);

            if (!class_exists($class)) {
                continue;
            }

            $refl = new \ReflectionClass($class);
            foreach ($refl->getAttributes(Map::class) as $attribute) {
                $source = $attribute->newInstance()->source;
                if ($source && class_exists($source)) {
                    $classMap[$source] = $class;
                }
            }
        }

        // Scan Resource classes to find entity Collection properties that map to resource array properties
        $resourceDir = \dirname(__DIR__).'/Api/Resource';
        foreach (glob($resourceDir.'/**/*.php') as $file) {
            $subDir = basename(\dirname($file));
            $class = 'App\\Api\\Resource\\'.$subDir.'\\'.pathinfo($file, \PATHINFO_FILENAME);
            if (!class_exists($class)) {
                continue;
            }

            $refl = new \ReflectionClass($class);

            // Find the entity class from #[Map(source: EntityClass)]
            $entityClass = null;
            foreach ($refl->getAttributes(Map::class) as $attribute) {
                $entityClass = $attribute->newInstance()->source;
            }

            if (!$entityClass || !class_exists($entityClass)) {
                continue;
            }

            // Add to classMap if no embedded class already covers this entity
            if (!isset($classMap[$entityClass])) {
                $classMap[$entityClass] = $class;
            }

            // Find entity Collection properties that have a matching array property on the resource
            $entityRefl = new \ReflectionClass($entityClass);
            foreach ($entityRefl->getProperties() as $entityProp) {
                $type = $entityProp->getType();
                if (!$type instanceof \ReflectionNamedType) {
                    continue;
                }

                if (!is_a($type->getName(), Collection::class, true)) {
                    continue;
                }

                // Check if the resource has a matching array property
                if ($refl->hasProperty($entityProp->getName())) {
                    $resourceProp = $refl->getProperty($entityProp->getName());
                    $resourceType = $resourceProp->getType();
                    if ($resourceType instanceof \ReflectionNamedType && 'array' === $resourceType->getName()) {
                        $propName = $entityProp->getName();
                        $collectionProperties[$entityClass][] = $propName;
                        $skipProperties[$entityClass][] = $propName;
                    }
                }
            }

            // Find entity object properties that map to embedded DTOs
            foreach ($entityRefl->getProperties() as $entityProp) {
                $type = $entityProp->getType();
                if (!$type instanceof \ReflectionNamedType) {
                    continue;
                }

                $typeName = $type->getName();

                // Skip collections (already handled) and non-classes
                if (!class_exists($typeName) || is_a($typeName, Collection::class, true)) {
                    continue;
                }

                // If this entity type is mapped to an Embedded class, skip it on reverse
                if (isset($classMap[$typeName])) {
                    $embeddedClass = $classMap[$typeName];
                    // Only skip if target is an Embedded (not a Resource)
                    if (str_starts_with($embeddedClass, 'App\\Api\\Embedded\\')) {
                        $propName = $entityProp->getName();
                        if ($refl->hasProperty($propName)) {
                            $skipProperties[$entityClass][] = $propName;
                        }
                    }
                }
            }
        }

        $definition = $container->getDefinition(ReverseClassObjectMapperMetadataFactory::class);
        $definition->setArgument('$classMap', $classMap);
        $definition->setArgument('$collectionProperties', $collectionProperties);
        $definition->setArgument('$skipProperties', $skipProperties);
    }
}
