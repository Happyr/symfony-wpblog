<?php

namespace Happyr\WordpressBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle.
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wordpress');
        $rootNode->children()
            ->scalarNode('url')->cannotBeEmpty()->end()
            ->arrayNode('cache')
                ->children()
                    ->scalarNode('service')->cannotBeEmpty()->end()
                    ->integerNode('timeout')->defaultValue(3600)->end()
                ->end()
            ->end();
    }
}
