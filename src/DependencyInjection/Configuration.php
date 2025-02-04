<?php

namespace NicolasJoubert\GrabitFrontFeedBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    #[\Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('grabit_front_feed');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $this->addClassSection($rootNode);
        $this->addDefaultSection($rootNode);

        return $treeBuilder;
    }

    protected function addClassSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('class')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('feed')->defaultValue('App\Entity\Feed')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    protected function addDefaultSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->booleanNode('use_cache')->defaultValue(true)->end()
            ->end()
        ;
    }
}
