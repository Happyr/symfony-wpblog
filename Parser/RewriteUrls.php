<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Parser;

use Happyr\WordpressBundle\Event\PageEvent;
use Happyr\WordpressBundle\Model\Page;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * This parser will rewrite all URLs. This is a "fallback" that handles
 * everything.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class RewriteUrls implements PageParserInterface
{

    /** @var string */
    private $remoteUrl;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function parsePage(Page $page): void
    {
        // TODO: Implement parsePage() method.
    }


}
