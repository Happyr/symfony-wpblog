<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Model;

class Category
{
    /**
     * @var Category[]
     */
    private $category;

    public function __construct(array $category)
    {
        if (empty($category)) {
            return;
        }

        $this->category = $category;
    }
}