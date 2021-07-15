<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Tests\Parser;

use Happyr\WordpressBundle\Model\Page;
use Happyr\WordpressBundle\Parser\RewriteUrls;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGenerator;

class RewriteUrlsTest extends TestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testRewrite($property, $input, $output)
    {
        $router = $this->getMockBuilder(UrlGenerator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['generate'])
            ->getMock();
        $router->expects($this->any())
            ->method('generate')
            ->willReturn('https://example.com/foo');

        $page = $this->getMockBuilder(Page::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getContent', 'setContent', 'getExcerpt', 'setExcerpt'])
            ->getMock();
        $page->expects($this->once())
            ->method('get'.$property)
            ->willReturn($input);

        $page->expects($this->once())
            ->method('set'.$property)
            ->with($output);

        $parser = new RewriteUrls('http://wordpress.com/wp-json/wp/v2/', $router);
        $parser->parsePage($page);
    }

    public function urlProvider()
    {
        yield ['content', 'http://wordpress.com/test', 'https://example.com/test'];
        yield ['content', 'http://wordpress.com/test/bar', 'https://example.com/test/bar'];
    }
}
