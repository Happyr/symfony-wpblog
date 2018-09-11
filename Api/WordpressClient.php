<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Api;

use Happyr\WordpressBundle\Model\Menu;
use Happyr\WordpressBundle\Model\Page;

class WordpressClient
{
    /**
     * @var CacheInterface
     */
    private $cache;

    public function getPage($idOrSlug): Page
    {

    }

    public function getMenu($idOrSlug): Menu
    {

    }
}