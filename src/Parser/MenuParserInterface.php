<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Parser;

use Happyr\WordpressBundle\Model\Menu;

interface MenuParserInterface
{
    public function parseMenu(Menu $menu): void;
}
