<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Parser;

use Happyr\WordpressBundle\Model\Category;
use Happyr\WordpressBundle\Model\Menu;

interface CategoryParserInterface
{
    public function parseCategory(Category $category): void;
}
