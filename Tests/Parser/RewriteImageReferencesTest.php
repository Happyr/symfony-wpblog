<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Tests\Parser;

use Happyr\WordpressBundle\Model\Page;
use Happyr\WordpressBundle\Parser\RewriteImageReferences;
use Happyr\WordpressBundle\Parser\RewriteLinks;
use Happyr\WordpressBundle\Parser\RewriteUrls;
use Happyr\WordpressBundle\Service\ImageUploaderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RewriteImageReferencesTest extends TestCase
{
    /**
     * @dataProvider pageProvider
     */
    public function testRewrite($apiUrl, $input, $inputUrl, $output, $outputUrl)
    {
        $router = $this->getMockBuilder(ImageUploaderInterface::class)
            ->setMethods(['uploadImage'])
            ->getMock();
        $router->expects($this->once())
            ->method('uploadImage')
            ->with($inputUrl)
            ->willReturn($outputUrl);

        $page = $this->getMockBuilder(Page::class)->getMock();
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

        $parser = new RewriteImageReferences($apiUrl, $router);
        $parser->parsePage($page);
    }

    public function pageProvider()
    {
        $apiUrl = 'http://wordpress.com/wp-json/wp/v2/';
        $input = 'Foo<img src="http://wordpress.com/wp-conent/uploads/2018/foobar.jpg" />Bar';
        $inputUrl = 'http://wordpress.com/wp-conent/uploads/2018/foobar.jpg';
        $outputUrl = '/uploads/foobar.jpg';
        $output = 'Foo<img src="/uploads/foobar.jpg" />Bar';

        yield [$apiUrl, $input, $inputUrl, $output, $outputUrl];
        yield [$apiUrl, 'Foo<img src="/wp-conent/uploads/2018/foobar.jpg" />Bar', '/wp-conent/uploads/2018/foobar.jpg', $output, $outputUrl];
        yield [$apiUrl, 'Foo<img class="xx" src="http://wordpress.com/wp-conent/uploads/2018/foobar.jpg" />Bar', $inputUrl, 'Foo<img class="xx" src="/uploads/foobar.jpg" />Bar', $outputUrl];
    }
}
