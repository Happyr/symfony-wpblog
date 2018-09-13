<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Service;

/**
 * Handle images from WordPress. The ImageUploader should download the image from
 * WordPress and upload it somewhere else. It should also return the URL to the new
 * location.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface ImageUploaderInterface
{
    public function uploadImage(string $url): string;
}
