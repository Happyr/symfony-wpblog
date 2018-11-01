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

        if (!$contentUrl) {
            return $content;
        }

        if (!empty($contentUrl['host']) && $remoteUrl['host'] === $contentUrl['host']) {

            // rewrite the URL.
            $content = $this->imageUploader->uploadImage($content);
        }

        return $content;
    }
}
