<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Model;

class Menu
{
    /** @var MenuItem[] */
    private $items = [];

    public function __construct(array $data = [])
    {
        if (empty($data)) {
            return;
        }

        foreach ($data as $d) {
            $this->items[] = new MenuItem($d);
        }
    }
}
