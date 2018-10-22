<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Service;

/**
 * Store images on local disk.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class LocalImageUploader implements ImageUploaderInterface
{
    /**
     * @var string The absolute path to a writeable directory
     */
    private $uploadsFolder;

    /**
     * @var string the prefix that should be before the file name on the URL
     */
    private $webPrefix;

    public function __construct(string $uploadsFolder, string $webPrefix)
    {
        $this->uploadsFolder = \rtrim($uploadsFolder, '/');
        $this->webPrefix = \rtrim($webPrefix, '/');
    }

    public function uploadImage(string $url): string
    {
        //Download the file
        $file = @\file_get_contents($url);
        if (false === $file) {
            return '';
        }

        $filename = \basename($url);

        // Check if file already exists
        if (\file_exists(\sprintf('%s/%s', $this->uploadsFolder, $filename))) {
            return \sprintf('%s/%s', $this->webPrefix, $filename);
        }

        // Save the file
        \file_put_contents(\sprintf('%s/%s', $this->uploadsFolder, $filename), $file);

        return \sprintf('%s/%s', $this->webPrefix, $filename);
    }
}
