<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Model;

class Page
{
    /** @var int */
    private $id;
    /** @var string */
    private $guid;
    /** @var \DateTimeImmutable */
    private $createdAt;
    /** @var \DateTimeImmutable */
    private $updatedAt;
    /** @var string */
    private $slug;
    /** @var string */
    private $status;
    /** @var string */
    private $type;
    /** @var string */
    private $link;
    /** @var string */
    private $title;
    /** @var string */
    private $content;
    /** @var string */
    private $excerpt;
    /** @var int */
    private $author;
    /** @var int */
    private $featuredMedia;
    /** @var string */
    private $commentStatus;
    /** @var string */
    private $pingStatus;
    /** @var bool */
    private $sticky;
    /** @var string */
    private $template;
    /** @var string */
    private $format;
    /** @var array */
    private $meta;
    /** @var array */
    private $categories;
    /** @var array */
    private $tags;

    public function __construct(array $data = [])
    {
        if (empty($data)) {
            return;
        }

        // Set dates
        $utc = new \DateTimeZone('UTC');
        $this->createdAt = new \DateTimeImmutable($data['date_gmt'], $utc);
        $this->updatedAt = new \DateTimeImmutable($data['modified_gmt'], $utc);
        $diff = $this->createdAt->diff(new \DateTimeImmutable($data['date']));
        $seconds = $diff->h * 60 * 60 + $diff->m * 60;
        $tz = timezone_name_from_abbr('', $seconds, 1);
        // Workaround for bug #44780
        if (false === $tz) {
            $tz = timezone_name_from_abbr('', $seconds, 0);
        }
        $localTimeZone = new \DateTimeZone($tz);
        $this->createdAt = $this->createdAt->setTimezone($localTimeZone);
        $this->updatedAt = $this->updatedAt->setTimezone($localTimeZone);

        $this->id = $data['id'];
        $this->guid = $data['guid']['rendered'];
        $this->slug = $data['slug'];
        $this->status = $data['status'];
        $this->type = $data['type'];
        $this->link = $data['link'];
        $this->title = $data['title']['rendered'];
        $this->content = $data['content']['rendered'];
        $this->excerpt = $data['excerpt']['rendered'];
        $this->author = $data['author'];
        $this->featuredMedia = $data['featured_media'];
        $this->commentStatus = $data['comment_status'];
        $this->pingStatus = $data['ping_status'];
        $this->sticky = $data['sticky'];
        $this->template = $data['template'];
        $this->format = $data['format'];
        $this->meta = $data['meta'];
        $this->categories = $data['categories'];
        $this->tags = $data['tags'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function setGuid(string $guid): void
    {
        $this->guid = $guid;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    public function setExcerpt(string $excerpt): void
    {
        $this->excerpt = $excerpt;
    }

    public function getAuthor(): int
    {
        return $this->author;
    }

    public function setAuthor(int $author): void
    {
        $this->author = $author;
    }

    public function getFeaturedMedia(): int
    {
        return $this->featuredMedia;
    }

    public function setFeaturedMedia(int $featuredMedia): void
    {
        $this->featuredMedia = $featuredMedia;
    }

    public function getCommentStatus(): string
    {
        return $this->commentStatus;
    }

    public function setCommentStatus(string $commentStatus): void
    {
        $this->commentStatus = $commentStatus;
    }

    public function getPingStatus(): string
    {
        return $this->pingStatus;
    }

    public function setPingStatus(string $pingStatus): void
    {
        $this->pingStatus = $pingStatus;
    }

    public function isSticky(): bool
    {
        return $this->sticky;
    }

    public function setSticky(bool $sticky): void
    {
        $this->sticky = $sticky;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function setMeta(array $meta): void
    {
        $this->meta = $meta;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }
}
