<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Tests\Parser;

use Happyr\WordpressBundle\Model\Page;
use Happyr\WordpressBundle\Parser\RewriteLinks;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RewriteLinksTest extends TestCase
{
    /**
     * @dataProvider pageProvider
     */
    public function testRewrite($input, $output, $apiUrl, $newUrl, $routeParams)
    {
        $router = $this->getMockBuilder(UrlGenerator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['generate'])
            ->getMock();
        $router->expects($this->once())
            ->method('generate')
            ->with('happyr_wordpress_page', $routeParams)
            ->willReturn($newUrl);

        $page = $this->getMockBuilder(Page::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getContent', 'setContent', 'getExcerpt', 'setExcerpt'])
            ->getMock();
        $page->expects($this->once())
            ->method('getContent')
            ->willReturn($input);

        $page->expects($this->once())
            ->method('setContent')
            ->with($output);

        $page->expects($this->once())
            ->method('getExcerpt')
            ->willReturn('text');
        $page->expects($this->once())
            ->method('setExcerpt')
            ->with('text');

        $parser = new RewriteLinks($apiUrl, $router);
        $parser->parsePage($page);
    }

    public function pageProvider()
    {
        $apiUrl = 'http://wordpress.com/wp-json/wp/v2/';
        $routeParams = ['slug' => 'foobar'];
        $newUrl = 'https://new-url.com/foobar';
        $input = '<b><a class="xx" href="http://wordpress.com/page/foobar">Click me</a></span>';
        $output = '<b><a class="xx" href="https://new-url.com/foobar">Click me</a></span>';

        yield [$input, $output, $apiUrl, $newUrl, $routeParams];
        yield ['<b><a class="xx" href="http://wordpress.com/post/foobar">Click me</a></span>', $output, $apiUrl, $newUrl, $routeParams];
        yield ['<a class="xx" href="http://wordpress.com/page/foo/bar">Click me</a>', '<a class="xx" href="https://new-url.com/foo/bar">Click me</a>', $apiUrl, 'https://new-url.com/foo/bar', ['slug' => 'foo/bar']];
        yield ['<a class="xx" href=\'http://wordpress.com/page/foobar\'>Click me</a>', '<a class="xx" href=\'https://new-url.com/foobar\'>Click me</a>', $apiUrl, $newUrl, $routeParams];
    }
}
