<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Api;

use Happyr\WordpressBundle\Model\Menu;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A super simple API client.
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
        $request = $this->requestFactory->createRequest('GET', $this->baseUrl.'/wp/v2/posts'.$query);
        $response = $this->httpClient->sendRequest($request);

        return $this->jsonDecode($response);
    }

    public function getPage(string $slug): array
    {
        // Check pages
        $request = $this->requestFactory->createRequest('GET', $this->baseUrl.'/wp/v2/pages?slug='.$slug);
        $response = $this->httpClient->sendRequest($request);

        $data = $this->jsonDecode($response);
        if (count($data) >= 1) {
            return $data[0];
        }

        // Check posts
        $request = $this->requestFactory->createRequest('GET', $this->baseUrl.'/wp/v2/posts?slug='.$slug);
        $response = $this->httpClient->sendRequest($request);

        $data = $this->jsonDecode($response);
        if (count($data) >= 1) {
            return $data[0];
        }

        return [];
    }

    /**
     * This requires the https://wordpress.org/plugins/tutexp-rest-api-menu/ to be installed.
     */
    public function getMenu(string $slug): array
    {
        $request = $this->requestFactory->createRequest('GET', $this->baseUrl.'/tutexpmenu/v2/menus/'.$slug);
        $response = $this->httpClient->sendRequest($request);

        $data = $this->jsonDecode($response);
        if (count($data) >= 1) {
            return $data;
        }

        return [];
    }

    /**
     * Generic get.
     * @param string $uri example "/wp/v2/categories"
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getUri(string $uri): array
    {
        $request = $this->requestFactory->createRequest('GET', $this->baseUrl.$uri);
        $response = $this->httpClient->sendRequest($request);

        $data = $this->jsonDecode($response);
        if (empty($data)) {
            return [];
        }

        return $data;
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

        if (!is_array($data)) {
            return [];
        }

        return $data;
    }
}
