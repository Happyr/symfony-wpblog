<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Api;

use Happyr\WordpressBundle\Model\Menu;
use Happyr\WordpressBundle\Model\Page;
    use Psr\Http\Client\ClientInterface;
use Symfony\Contracts\Cache\CacheInterface;

class WordpressClient
{
    private $cache;

    private $httpClient;

    public function __construct(ClientInterface $httpClient, CacheInterface $cache)
    {
        $this->cache = $cache;
        $this->httpClient = $httpClient;
    }


    public function getPage($idOrSlug): Page
    {
    }

    public function getMenu($idOrSlug): Menu
    {
    }
}
