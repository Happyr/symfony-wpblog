<?php

namespace Happyr\WordpressBundle\DependencyInjection;

use Happyr\WordpressBundle\Controller\WordpressController;
use Happyr\WordpressBundle\Parser\RewriteImageReferences;
use Happyr\WordpressBundle\Parser\RewriteLinks;
use Happyr\WordpressBundle\Parser\RewriteUrls;
use Happyr\WordpressBundle\Service\LocalImageUploader;
use Happyr\WordpressBundle\Service\Wordpress;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class WordpressExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $remoteUrl = rtrim($config['url'], '/');
        $container->setParameter('happyr_wordpress.remote_url', $remoteUrl);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        if ($config['controller']['enabled']) {
            $loader->load('controller.yaml');
            $container->getDefinition(WordpressController::class)
                ->replaceArgument(1, $config['controller']['index_template'])
                ->replaceArgument(2, $config['controller']['page_template'])
                ->replaceArgument(3, $config['controller']['allow_invalidate']);
        }

        $container->getDefinition(Wordpress::class)
            ->replaceArgument(2, new Reference($config['cache']['service']))
            ->replaceArgument(3, $config['cache']['ttl']);

        $container->getDefinition(LocalImageUploader::class)
            ->replaceArgument(0, $config['local_image_uploader']['local_path'])
            ->replaceArgument(1, $config['local_image_uploader']['public_prefix']);

        $this->configureParsers($container, $config['parser']);
    }

    private function configureParsers(ContainerBuilder $container, array $config)
    {
        $parsers = ['image' => RewriteImageReferences::class, 'link' => RewriteLinks::class, 'url' => RewriteUrls::class];
        foreach ($parsers as $key => $serviceId) {
            if (!$config[$key]['enabled']) {
                $container->removeDefinition($serviceId);
            }
        }

        if ($config['image']['enabled']) {
            $container->getDefinition(RewriteImageReferences::class)
                ->replaceArgument(1, new Reference($config['image']['uploader']));
        }
    }
}
