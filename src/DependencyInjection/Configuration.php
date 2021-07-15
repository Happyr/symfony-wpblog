<?php

namespace Happyr\WordpressBundle\DependencyInjection;

use Happyr\WordpressBundle\Service\LocalImageUploader;
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
        $treeBuilder = new TreeBuilder('wordpress');
        $root = $treeBuilder->getRootNode();

        $root->children()
            ->scalarNode('url')->cannotBeEmpty()->isRequired()->end()
            ->arrayNode('cache')
                ->children()
                    ->scalarNode('service')->cannotBeEmpty()->isRequired()->end()
                    ->integerNode('ttl')->defaultValue(3600)->end()
                ->end()
            ->end()
            ->arrayNode('controller')
                ->canBeDisabled()
                ->children()
                    ->scalarNode('index_template')->defaultValue('@Wordpress/index.html.twig')->end()
                    ->scalarNode('page_template')->defaultValue('@Wordpress/page.html.twig')->end()
                    ->booleanNode('allow_invalidate')->defaultTrue()->info('Add an endpoint for invalidating pages')->end()
                ->end()
            ->end()
            ->arrayNode('local_image_uploader')
                ->canBeDisabled()
                ->children()
                    ->scalarNode('local_path')->defaultValue('%kernel.project_dir%/public/uploads')->end()
                    ->scalarNode('public_prefix')->defaultValue('/uploads')->end()
                ->end()
            ->end()
            ->arrayNode('parser')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('image')
                        ->canBeDisabled()
                        ->children()
                            ->scalarNode('uploader')->defaultValue(LocalImageUploader::class)->end()
                        ->end()
                    ->end()
                    ->arrayNode('link')
                        ->canBeDisabled()
                        ->children()->end()
                    ->end()
                    ->arrayNode('url')
                        ->canBeDisabled()
                        ->children()->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
