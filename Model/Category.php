<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Model;


class Category
{
    /**
     * @var Category[]
     */
    private $categories;

    public function __construct(array $categories)
    {
        if (empty($categories)) {
            return;
        }

        $this->categories = $categories;
    }
}