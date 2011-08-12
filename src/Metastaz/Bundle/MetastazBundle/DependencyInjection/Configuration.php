<?php

namespace Metastaz\Bundle\MetastazBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('metastaz');
/*
        $rootNode
            ->children()
                ->arrayNode('container')
                    ->children()
                        ->booleanNode('use_template')->defaultValue('true')->end()
                    ->end()
                ->arrayNode('store')
                    ->children()
                        ->scalarNode('class')->defaultValue('DoctrineORMMetastazStore')->end()
                        ->arrayNode('parameters')
                            ->children()
                                ->scalarNode('connection')->defaultValue('metastaz')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
*/
        return $treeBuilder;
    }
}
