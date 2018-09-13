<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Parser;

use Happyr\WordpressBundle\Model\Menu;
use Happyr\WordpressBundle\Model\Page;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class RewriteLinks implements PageParserInterface, MenuParserInterface
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
        if (!preg_match_all('|href=(?P<quote>[\'"])(.+?)(?P=quote)|si', $content, $matches)) {
            return $content;
        }

        $remoteUrl = parse_url($this->remoteUrl);
        for ($i = 0; $i < count($matches[0]); $i++) {
            $url = $matches[2][$i];
            $testUrl = parse_url($url);
            if ($testUrl['host'] !== $remoteUrl['host']) {
                continue;
            }
            if (preg_match('@/(?:post|page)/(.*)@si', $testUrl['path'], $urlMatch)) {
                $replacement = $this->urlGenerator->generate('happyr_wordpress_page', ['slug'=>$urlMatch[1]]);
                $content = str_replace($url, $replacement, $content);
            }
        }

        return $content;
    }

}
