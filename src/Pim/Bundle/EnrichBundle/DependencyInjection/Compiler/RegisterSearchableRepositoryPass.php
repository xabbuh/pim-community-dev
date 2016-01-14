<?php

namespace Pim\Bundle\EnrichBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author    Adrien Pétremann <adrien.petremann@akeneo.com>
 * @copyright 2016 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class RegisterSearchableRepositoryPass implements CompilerPassInterface
{
    /** @staticvar string */
    const SEARCHABLE_REPOSITORY_TAG = 'pim_enrich.repository.searchable';

    /** @staticvar string */
    const SEARCHABLE_REPOSITORY_REGISTRY = 'pim_enrich.registry.repository.searchable';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::SEARCHABLE_REPOSITORY_REGISTRY)) {
            throw new \LogicException('Searchable repository registry must be configured');
        }

        $registry = $container->getDefinition(self::SEARCHABLE_REPOSITORY_REGISTRY);
        $taggedServices = $container->findTaggedServiceIds(self::SEARCHABLE_REPOSITORY_TAG);

        foreach ($taggedServices as $id => $attributes) {
            $attribute = current($attributes);
            $alias     = $attribute['alias'];
            $entity    = $attribute['entity'];

            $registry->addMethodCall('register', [$alias, $entity, new Reference($id)]);
        }
    }
}
