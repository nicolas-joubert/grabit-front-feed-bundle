<?php

namespace NicolasJoubert\GrabitFrontFeedBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @phpstan-type GrabitFrontFeedConfiguration array{
 *     'class': array{'feed': string},
 *     'use_cache': bool
 * }
 */
class GrabitFrontFeedExtension extends Extension
{
    /**
     * @param GrabitFrontFeedConfiguration $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(\dirname(__DIR__).'/../config'));
        $loader->load('services.yaml');

        if ('prod' === $container->getParameter('kernel.environment')) {
            $loader->load('services_prod.yaml');
        }

        /** @var GrabitFrontFeedConfiguration $config */
        $config = $this->processConfiguration($this->getConfiguration($configs, $container), $configs);

        // Models
        $container->setParameter('grabit_front_feed.model.feed.class', $config['class']['feed']);

        // Cache
        if (!$config['use_cache']) {
            $container->removeDefinition('grabit_front_feed.manager.cached_feed_manager');
        }
    }
}
