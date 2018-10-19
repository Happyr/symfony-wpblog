<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Model;


class Media
{
    /**
     * @var Media[]
     */
    private $media;

    public function __construct(array $media)
    {
        if (empty($media)) {
            return;
        }

        $this->media = $media;
    }
}