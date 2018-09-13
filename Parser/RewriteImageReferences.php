<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Parser;

use Happyr\WordpressBundle\Model\Page;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class RewriteImageReferences implements PageParserInterface
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
        // TODO featured media?
    }


    private function rewrite(string $content): string
    {
        if (!preg_match_all('|src=(?P<quote>[\'"])(.+?)(?P=quote)|si', $content, $matches)) {
            return $content;
        }

        for ($i = 0; $i < count($matches[0]); $i++) {
            $url = $matches[2][$i];
            $testUrl = parse_url($url);
            $remoteUrl = parse_url($this->remoteUrl);
            if ($testUrl['host'] !== $remoteUrl['host']) {
                continue;
            }

            // TODO download the media and store it somewhere good.
            // TODO rewrite the URL.

        }

        return $content;
    }

}
