<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Tests\Parser;

use Happyr\WordpressBundle\Model\Page;
use Happyr\WordpressBundle\Event\PageEvent;
use Happyr\WordpressBundle\Parser\RewriteLinks;
use Happyr\WordpressBundle\Parser\RewriteUrls;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RewriteLinksTest extends TestCase
{

    public function testRewrite()
    {
        $router = $this->getMockBuilder(UrlGeneratorInterface::class)
            ->setMethods(['generate'])
            ->getMock();
        $router->expects($this->once())
            ->method('generate')
            ->with('wp_page', ['slug'=>'foobar'])
            ->willReturn('http://new-url.com/foobar');

        $page = $this->getMockBuilder(Page::class)->getMock();
        $page->expects($this->once())
            ->method('getContent')
            ->willReturn('<b><a class="xx" href="http://wordpress.com/page/foobar">Click me</a></span>');

        $page->expects($this->once())
            ->method('setContent')
            ->with('<b><a class="xx" href="http://new-url.com/foobar">Click me</a></span>');

        $page->expects($this->once())
            ->method('getExcerpt')
            ->willReturn('text');
        $page->expects($this->once())
            ->method('setExcerpt')
            ->with('text');

        $parser = new RewriteLinks('http://wordpress.com/wp-json/wp/v2/', $router);
        $parser->onParse(new PageEvent($page));
    }
}
