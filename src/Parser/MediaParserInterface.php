<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Parser;

use Happyr\WordpressBundle\Model\Media;

interface MediaParserInterface
{
    public function parseMedia(Media $media): void;
}
