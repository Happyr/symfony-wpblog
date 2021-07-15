<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Twig;

use Happyr\WordpressBundle\Model\Category;
use Happyr\WordpressBundle\Model\Media;
use Happyr\WordpressBundle\Service\Wordpress;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

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
            new TwigFunction('getWpMenu', [$this->wordpress, 'getMenu']),
            new TwigFunction('getWpPage', [$this->wordpress, 'getPage']),
            new TwigFunction('getWpCategoryById', [$this, 'getCategoryById']),
            new TwigFunction('getWpMediaById', [$this, 'getMediaById']),
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
