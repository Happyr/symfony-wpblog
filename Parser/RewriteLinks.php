<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Parser;

use Happyr\WordpressBundle\Event\PageEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class RewriteLinks implements EventSubscriberInterface
{
    private $remoteUrl;
    private $urlGenerator;

    public function __construct(string $remoteUrl, UrlGeneratorInterface $urlGenerator)
    {
        $this->remoteUrl = $remoteUrl;
        $this->urlGenerator = $urlGenerator;
    }


    public function onParse(PageEvent $event)
    {
        $page = $event->getPage();
        $page->setContent($this->rewrite($page->getContent()));
        $page->setExcerpt($this->rewrite($page->getExcerpt()));

    }

    private function rewrite(string $content): string
    {
        if (!preg_match_all('|href="([^"]+)"|sim', $content, $matches)) {
            return $content;
        }

        for ($i = 0; $i < count($matches[0]); $i++) {
            // TODO figure out if this should be replaced or not
            $url = $matches[1][$i];
            $testUrl = parse_url($url);
            $remoteUrl = parse_url($this->remoteUrl);
            if ($testUrl['host'] !== $remoteUrl['host']) {
                continue;
            }
            if (preg_match('@/(?:post|page)/(.*)@si', $testUrl['path'], $urlMatch)) {
                $replacement = $this->urlGenerator->generate('wp_page', ['slug'=>$urlMatch[1]]);
                $content = str_replace($url, $replacement, $content);
            }
        }

        return $content;
    }

    public static function getSubscribedEvents()
    {
        return [PageEvent::PARSE => 'onParse'];
    }

}
