<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Parser;

use Happyr\WordpressBundle\Model\Menu;
use Happyr\WordpressBundle\Model\Page;

/**
 * Parse raw data from the API into models.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class MessageParser
{
    /**
     * @var PageParserInterface[]
     */
    private $pageParsers;

    /**
     * @var MenuParserInterface[]
     */
    private $menuParsers;

    /**
     * @param PageParserInterface[] $pageParsers
     * @param MenuParserInterface[] $menuParsers
     */
    public function __construct(iterable $pageParsers, iterable $menuParsers)
    {
        $this->pageParsers = $pageParsers;
        $this->menuParsers = $menuParsers;
    }

    public function parsePage(array $data): ?Page
    {
        try {
            $page = new Page($data);
        } catch (\Throwable $t) {
            return null;
        }

        foreach ($this->pageParsers as $parser) {
            $parser->parsePage($page);
        }

        return $page;
    }

    public function parseMenu(array $data): ?Menu
    {
        try {
            $menu = new Menu($data);
        } catch (\Throwable $t) {
            return null;
        }

        foreach ($this->menuParsers as $parser) {
            $parser->parseMenu($menu);
        }

        return $menu;
    }
}
