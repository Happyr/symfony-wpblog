<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Parser;

use Happyr\WordpressBundle\Model\Media;
use Happyr\WordpressBundle\Service\ImageUploaderInterface;

class RewriteMediaUrl implements MediaParserInterface
{
    private $remoteUrl;
    private $imageUploader;

    public function __construct(string $remoteUrl, ImageUploaderInterface $imageUploader)
    {
        $this->remoteUrl = $remoteUrl;
        $this->imageUploader = $imageUploader;
    }

    public function parseMedia(Media $media): void
    {
        $media->setSourceUrl($this->rewriteUrl($media->getSourceUrl()));
    }

    private function rewriteUrl(string $content) :string
    {
        if (!preg_match_all('|<img[^>]+src=(?P<quote>[\'"])(.+?)(?P=quote)|sim', $content, $matches)) {
            return $content;
        }

        $remoteUrl = parse_url($this->remoteUrl);

        for ($i = 0; $i < count($matches[0]); ++$i) {
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