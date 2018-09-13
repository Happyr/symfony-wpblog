<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Parser;

use Happyr\WordpressBundle\Model\Page;

interface PageParserInterface
{
    public function parsePage(Page $page): void;
}
