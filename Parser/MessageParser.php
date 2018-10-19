<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Parser;

use Happyr\WordpressBundle\Model\Category;
use Happyr\WordpressBundle\Model\Menu;
use Happyr\WordpressBundle\Model\Page;

/**
 * Parse raw data from the API into models.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class MessageParser
{
    private $pageParsers;
    private $menuParsers;
    private $mediaParsers;
    private $categoryParsers;

    /**
     * @param PageParserInterface[] $pageParsers
     * @param MenuParserInterface[] $menuParsers
     * @param MediaParserInterface[] $mediaParsers
     * @param CategoryParserInterface[] $categoryParsers
     */
    public function __construct(iterable $pageParsers, iterable $menuParsers, iterable $mediaParsers, iterable $categoryParsers)
    {
        $this->pageParsers = $pageParsers;
        $this->menuParsers = $menuParsers;
        $this->mediaParsers = $mediaParsers;
        $this->categoryParsers = $categoryParsers;
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

    /**
     * @return Category[]
     */
    public function parseCategories(array $data): array
    {
        try {
            // TODO decide if it is one or more
            $category = new Category($data);
        } catch (\Throwable $t) {
            return null;
        }

        foreach ($this->categoryParsers as $parser) {
            $parser->parseCategory($category);
        }

        return [$category];
    }

    /**
     * @return Media[]
     */
    public function parseMedia(array $data): array
    {
        try {
            // TODO decide if it is one or more
            $category = new Media($data);
        } catch (\Throwable $t) {
            return null;
        }

        foreach ($this->mediaParsers as $parser) {
            $parser->parseMedia($category);
        }

        return [$category];
    }
}
