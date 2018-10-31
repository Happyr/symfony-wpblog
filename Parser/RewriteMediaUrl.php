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

    private function rewriteUrl(string $content): string
    {
        $remoteUrl = parse_url($this->remoteUrl);
        $contentUrl = parse_url($content);

        if (!$remoteUrl) {
            return $content;
        }

        if ($contentUrl['host'] && $remoteUrl['host'] === $contentUrl['host']) {
            $replacement = $this->imageUploader->uploadImage($content);

            // rewrite the URL.
            $content = str_replace($contentUrl, $replacement, $content);
        }

        return $content;
    }
}
