<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Service;

use Happyr\WordpressBundle\Api\WpClient;
use Happyr\WordpressBundle\Model\Menu;
use Happyr\WordpressBundle\Model\Page;
use Happyr\WordpressBundle\Parser\MessageParser;
use Psr\Cache\CacheItemInterface;
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
        $this->client = $client;
        $this->messageParser = $parser;
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    /**
     * Get a list of posts. Ie for the start page. Feel free to pass any query to
     * Wordpress API.
     */
    public function listPosts(string $query = ''): array
    {
        return $this->cache->get('post_query_'.$query, function (/*ItemInterface*/ CacheItemInterface $item) use ($query) {
            $data = $this->client->getPostList($query);
            if (empty($data)) {
                $item->expiresAfter(30);
                return null;
            }

            $item->expiresAfter($this->ttl);
            $pages = [];
            foreach($data as $d) {
                $pages[] = $this->messageParser->parsePage($d);
            }

            return $pages;
        });
    }

    public function getPage(string $slug): ?Page
    {
        return $this->cache->get('page_'.$slug, function (/*ItemInterface*/ CacheItemInterface $item) use ($slug) {
            $data = $this->client->getPage($slug);
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
        return $this->cache->get('menu_'.$slug, function (/*ItemInterface*/ CacheItemInterface $item) use ($slug) {
            $data = $this->client->getMenu($slug);
            if (empty($data)) {
                $item->expiresAfter(300);
                return null;
            }

            $item->expiresAfter($this->ttl);

            return $this->messageParser->parseMenu($data);
        });
    }

    /**
     * Purge cache for pages and menus
     */
    public function purgeCache(string $slug): void
    {
        // Make sure to expire item
        $callback = function (ItemInterface $item) {
            $item->expiresAfter(0);
        };

        // Get item and force recompute.
        $this->cache->get('page_'.$slug, $callback, INF);
        $this->cache->get('menu_'.$slug, $callback, INF);
        $this->cache->get('post_query_', $callback, INF);
    }
}
