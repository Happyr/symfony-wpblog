<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Service;

use Happyr\WordpressBundle\Api\WpClient;
use Happyr\WordpressBundle\Model\Menu;
use Happyr\WordpressBundle\Model\Page;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * This is the class you want to interact with. It fetches data from
 * cache or API
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Wordpress
{
    private $cache;
    private $client;
    private $messageParser;
    private $ttl;

    public function __construct(WpClient $client, MessageParser $parser, CacheInterface $cache, int $ttl)
    {
        $this->cache = $cache;
        $this->client = $client;
        $this->messageParser = $parser;
        $this->ttl = $ttl;
    }

    public function getPage(string $slug): ?Page
    {
        return $this->cache->get($slug, function (ItemInterface $item) {
            $data = $this->client->getPage($item->getKey());
            if (empty($data)) {
                $item->expiresAfter(300);
                return null;
            }

            $item->expiresAfter($this->ttl);

            return $this->messageParser->parsePage($data);
        });
    }

    public function getMenu(string $slug): ?Menu
    {
        return $this->cache->get($slug, function (ItemInterface $item) {
            $data = $this->client->getMenu($item->getKey());
            if (empty($data)) {
                $item->expiresAfter(300);
                return null;
            }

            $item->expiresAfter($this->ttl);

            return $this->messageParser->parseMenu($data);
        });
    }
}
