<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Service;

use Happyr\WordpressBundle\Api\WpClient;
use Happyr\WordpressBundle\Model\Menu;
use Happyr\WordpressBundle\Model\Page;
use Happyr\WordpressBundle\Parser\MessageParser;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * This is the class you want to interact with. It fetches data from
 * cache or API.
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
     *
     * {@link https://developer.wordpress.org/rest-api/reference/posts/#arguments}
     */
    public function listPosts(string $query = ''): array
    {
        return $this->cache->get($this->getCacheKey('query', $query), function (ItemInterface $item) use ($query) {
            $data = $this->client->getPostList($query);
            if (!$this->isValidResponse($data)) {
                $item->expiresAfter(30);

                return [];
            }

            $item->expiresAfter($this->ttl);
            $pages = [];
            foreach ($data as $d) {
                $pages[] = $this->messageParser->parsePage($d);
            }

            return $pages;
        });
    }

    public function getPage(string $slug): ?Page
    {
        return $this->cache->get($this->getCacheKey('page', $slug), function (ItemInterface $item) use ($slug) {
            $data = $this->client->getPage($slug);
            if (!$this->isValidResponse($data)) {
                $item->expiresAfter(300);

                return null;
            }

            $item->expiresAfter($this->ttl);

            return $this->messageParser->parsePage($data);
        });
    }

    public function getMenu(string $slug): ?Menu
    {
        return $this->cache->get($this->getCacheKey('menu', $slug), function (ItemInterface $item) use ($slug) {
            $data = $this->client->getMenu($slug);
            if (!$this->isValidResponse($data)) {
                $item->expiresAfter(300);

                return null;
            }

            $item->expiresAfter($this->ttl);

            return $this->messageParser->parseMenu($data);
        });
    }

    public function getCategories(string $slug = ''): array
    {
        return $this->cache->get($this->getCacheKey('categories', $slug), function (ItemInterface $item) use ($slug) {
            $data = $this->client->getUri('/wp/v2/categories'.$slug);
            if (!$this->isValidResponse($data)) {
                $item->expiresAfter(300);

                return null;
            }

            $item->expiresAfter($this->ttl);

            return $this->messageParser->parseCategories($data);
        });
    }

    public function getMedia(string $slug = ''): array
    {
        return $this->cache->get($this->getCacheKey('media', $slug), function (ItemInterface $item) use ($slug) {
            $data = $this->client->getUri('/wp/v2/media'.$slug);
            if (!$this->isValidResponse($data)) {
                $item->expiresAfter(300);

                return null;
            }

            $item->expiresAfter($this->ttl);

            return $this->messageParser->parseMedia($data);
        });
    }

    /**
     * Purge cache for pages and menus.
     */
    public function purgeCache(string $identifier): void
    {
        // Make sure to expire item
        $callback = function (ItemInterface $item) {
            $item->expiresAfter(0);
        };

        // Get item and force recompute.
        $this->cache->get($this->getCacheKey('page', $identifier), $callback, INF);
        $this->cache->get($this->getCacheKey('menu', $identifier), $callback, INF);
        $this->cache->get($this->getCacheKey('categories', $identifier), $callback, INF);
        $this->cache->get($this->getCacheKey('media', $identifier), $callback, INF);
        $this->cache->get($this->getCacheKey('query', $identifier), $callback, INF);
    }

    private function getCacheKey(string $prefix, string $identifier): string
    {
        return sha1($prefix.'_'.$identifier);
    }

    private function isValidResponse($data): bool
    {
        if (isset($data['code']) && isset($data['data']['status']) && 400 === $data['data']['status']) {
            return false;
        }

        return !empty($data);
    }
}
