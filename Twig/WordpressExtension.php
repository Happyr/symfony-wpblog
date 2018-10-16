<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Twig;

use Happyr\WordpressBundle\Service\Wordpress;
use Twig\Extension\AbstractExtension;

class WordpressExtension extends AbstractExtension
{
    private $wordpress;

    public function __construct(Wordpress $wp)
    {
        $this->wordpress = $wp;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getWpMenu', [$this->wordpress, 'getMenu']),
            new \Twig_SimpleFunction('getWpPage', [$this->wordpress, 'getPage']),
            new \Twig_SimpleFunction('getCategoryById', [$this, 'getCategoryById']),
            new \Twig_SimpleFunction('getMediaById', [$this, 'getMediaById']),
        ];
    }

    public function getMediaById($id): ?Media
    {
        $media = $this->wordpress->getMedia('/'.$id);
        foreach ($media as $m) {
            return $m;
        }

        return null;
    }

    public function getCategoryById($id): ?Category
    {
        $categories = $this->wordpress->getCategories('/'.$id);
        foreach ($categories as $category) {
            return $category;
        }

        return null;
    }
}
