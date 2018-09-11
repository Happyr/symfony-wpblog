<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Api;

use Happyr\WordpressBundle\Model\Menu;
use Happyr\WordpressBundle\Model\Page;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

/**
 * A super simple API client
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class WpClient
{
    private $baseUrl;
    private $httpClient;
    private $requestFactory;

    public function __construct(ClientInterface $httpClient, RequestFactoryInterface $requestFactory, string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->requestFactory = $requestFactory;
        $this->httpClient = $httpClient;
    }


    public function getPage(string $slug): array
    {
        // Check pages
        $request = $this->requestFactory->createRequest('GET', $this->baseUrl.'/pages?slug='.$slug);
        $response = $this->httpClient->sendRequest($request);

        // Check posts
        $request = $this->requestFactory->createRequest('GET', $this->baseUrl.'/posts?slug='.$slug);
        $response = $this->httpClient->sendRequest($request);
    }


    public function getMenu(string $slug): array
    {
        // Install special plugin (https://sv.wordpress.org/plugins/wp-rest-api-v2-menus/)

    }
}
