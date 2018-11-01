<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Model;

class Category
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $count;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $link;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $taxonomy;

    /**
     * @var int
     */
    private $parentId;

    public function __construct(array $data)
    {
        if (empty($data)) {
            return;
        }

        $this->id = $data['id'];
        $this->count = $data['count'];
        $this->description = $data['description'];
        $this->link = $data['link'];
        $this->name = $data['name'];
        $this->slug = $data['slug'];
        $this->taxonomy = $data['taxonomy'];
        $this->parentId = $data['parent'];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getTaxonomy(): string
    {
        return $this->taxonomy;
    }

    public function getParentId(): int
    {
        return $this->parentId;
    }
}
