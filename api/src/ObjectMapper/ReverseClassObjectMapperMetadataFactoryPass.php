<?php

declare(strict_types=1);

namespace App\ObjectMapper;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Injects the class map from tagged resources into the ReverseClassObjectMapperMetadataFactory
 */
final class ReverseClassObjectMapperMetadataFactoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(ReverseClassObjectMapperMetadataFactory::class)) {
            return;
        }

        $definition = $container->getDefinition(ReverseClassObjectMapperMetadataFactory::class);

        // Collect all tagged resources with 'object_mapper.map'
        $classes = [];
        foreach ($container->findTaggedResourceIds('object_mapper.map') as $tags) {
            foreach ($tags as $tag) {
                if (isset($tag['source'], $tag['target'])) {
                    $classes[$tag['source']] = $tag['target'];
                }
            }
        }

        $definition->setArgument('$classMap', $classes);
    }
}
