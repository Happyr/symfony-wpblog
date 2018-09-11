<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Service;

use Happyr\WordpressBundle\Model\Menu;
use Happyr\WordpressBundle\Model\Page;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Parse raw data from the API into models.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class MessageParser
{
    private $eventDispatcher;

    /**
     *
     * @param $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function parsePage(array $data): ?Page
    {
        $page = new Page($data);

        // TODO dispatch event

        return $page;
    }
    public function parseMenu(array $data): ?Menu
    {

    }
}
