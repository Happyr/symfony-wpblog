<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Api;

use Happyr\WordpressBundle\Model\Menu;
use Happyr\WordpressBundle\Model\Page;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;

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

    public function getPostList(string $query): array
    {
        $request = $this->requestFactory->createRequest('GET', $this->baseUrl.'/posts'.$query);
        $response = $this->httpClient->sendRequest($request);

        return $this->jsonDecode($response);
    }

    public function getPage(string $slug): array
    {
        // Check pages
        $request = $this->requestFactory->createRequest('GET', $this->baseUrl.'/pages?slug='.$slug);
        $response = $this->httpClient->sendRequest($request);

        $data = $this->jsonDecode($response);
        if (count($data) >= 1) {
            return $data[0];
        }

        // Check posts
        $request = $this->requestFactory->createRequest('GET', $this->baseUrl.'/posts?slug='.$slug);
        $response = $this->httpClient->sendRequest($request);

        $data = $this->jsonDecode($response);
        if (count($data) >= 1) {
            return $data[0];
        }

        return [];
    }


    public function getMenu(string $slug): array
    {
        // Install special plugin (https://sv.wordpress.org/plugins/wp-rest-api-v2-menus/)

    }

    private function jsonDecode(ResponseInterface $response): array
    {
        $body = $response->getBody()->__toString();
        $contentType = $response->getHeaderLine('Content-Type');
        if (0 !== strpos($contentType, 'application/json') && 0 !== strpos($contentType, 'application/octet-stream')) {
            throw new \RuntimeException('The ModelHydrator cannot hydrate response with Content-Type: '.$contentType);
        }
        $data = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(sprintf('Error (%d) when trying to json_decode response', json_last_error()));
        }

        return $data;
    }
}
