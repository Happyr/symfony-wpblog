<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Parser;

use Happyr\WordpressBundle\Model\Page;
use Happyr\WordpressBundle\Service\ImageUploaderInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class RewriteImageReferences implements PageParserInterface
{
    private $remoteUrl;
    private $imageUploader;

    public function __construct(string $remoteUrl, ImageUploaderInterface $imageUploader)
    {
        $this->remoteUrl = $remoteUrl;
        $this->imageUploader = $imageUploader;
    }

    public function parsePage(Page $page): void
    {
        $page->setContent($this->rewrite($page->getContent()));
        $page->setExcerpt($this->rewrite($page->getExcerpt()));
        // TODO featured media?
    }


    private function rewrite(string $content): string
    {
        if (!preg_match_all('|<img[^>]+src=(?P<quote>[\'"])(.+?)(?P=quote)|sim', $content, $matches)) {
            return $content;
        }

        $remoteUrl = parse_url($this->remoteUrl);

        for ($i = 0; $i < count($matches[0]); $i++) {
            $url = $matches[2][$i];
            $testUrl = parse_url($url);
            if (!empty($testUrl['host']) && $testUrl['host'] !== $remoteUrl['host']) {
                continue;
            }

            // download the media and store it somewhere good.
            $replacement = $this->imageUploader->uploadImage($url);

            // rewrite the URL.
            $content = str_replace($url, $replacement, $content);
        }

        return $content;
    }

}
