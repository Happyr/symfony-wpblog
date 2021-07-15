<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Model;

class MenuItem
{
    /** @var int */
    private $id;

    /** @var int */
    private $parentId;

    /** @var string */
    private $title;

    /** @var string */
    private $type;

    /** @var string */
    private $icon;

    public function __construct(array $data = [])
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('You must provide an array with data.');
        }

        $this->id = (int) $data['id'];
        $this->parentId = (int) $data['parent_id'];
        $this->title = $data['title'];
        $this->type = $data['type'];
        $this->icon = $data['icon'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getParentId(): int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }
}
