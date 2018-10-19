<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Parser;

use Happyr\WordpressBundle\Model\Media;
use Happyr\WordpressBundle\Model\Menu;

interface MediaParserInterface
{
    public function parseMedia(Media $media): void;
}
