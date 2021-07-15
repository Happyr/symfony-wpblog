<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Parser;

use Happyr\WordpressBundle\Model\Menu;
use Happyr\WordpressBundle\Model\Page;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * This parser will rewrite all URLs. This is a "fallback" that handles
 * everything.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class RewriteUrls implements PageParserInterface
{
    private $remoteUrl;
    private $urlGenerator;

    public function __construct(string $remoteUrl, UrlGeneratorInterface $urlGenerator)
    {
        $this->remoteUrl = $remoteUrl;
        $this->urlGenerator = $urlGenerator;
    }

    public function parsePage(Page $page): void
    {
        $page->setContent($this->rewrite($page->getContent()));
        $page->setExcerpt($this->rewrite($page->getExcerpt()));
    }

    public function parseMenu(Menu $menu): void
    {
        // TODO: Implement parseMenu() method.
    }

    private function rewrite(string $content): string
    {
        // Find current URL host
        $url = $this->urlGenerator->generate('happyr_wordpress_page', ['slug' => 'foo'], UrlGeneratorInterface::ABSOLUTE_URL);
        $localParts = parse_url($url);

        $remoteParts = parse_url($this->remoteUrl);

        $buildUrl = function ($parts) {
            return sprintf('%s://%s', $parts['scheme'], $parts['host']);
        };

        return str_replace($buildUrl($remoteParts), $buildUrl($localParts), $content);
    }
}
