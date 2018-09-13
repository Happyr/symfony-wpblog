<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Event;

use Happyr\WordpressBundle\Model\Page;
use Symfony\Component\EventDispatcher\Event;

class PageEvent extends Event
{
    const PARSE = 'happyr_wordpress.page.parse';

    private $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    public function getPage(): Page
    {
        return $this->page;
    }
}
