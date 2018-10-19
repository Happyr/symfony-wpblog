<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Model;

class Media
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $link;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $souceUrl;

    public function __construct(array $data)
    {
        if (empty($data)) {
            return;
        }

        $this->id = $data['id'];
        $this->link = $data['link'];
        $this->slug = $data['slug'];
        $this->souceUrl = $data['source_url'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getSouceUrl(): string
    {
        return $this->souceUrl;
    }
}
