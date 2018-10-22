<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Tests\Parser;

use Happyr\WordpressBundle\Model\Media;
use Happyr\WordpressBundle\Parser\RewriteMediaUrl;
use Happyr\WordpressBundle\Service\ImageUploaderInterface;
use PHPUnit\Framework\TestCase;

class RewriteMediaUrlTest extends TestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testRewrite($inputUrl, $outputUrl)
    {
        $router = $this->getMockBuilder(ImageUploaderInterface::class)
            ->setMethods(['uploadImage'])
            ->getMock();

        $media = $this->getMockBuilder(Media::class)
            ->getMock();

        $media->method('getSourceUrl')->willReturn($inputUrl);

        $media->expects($this->once())
            ->method('setSourceUrl');

        $parser = new RewriteMediaUrl('http://wordpress.com/wp-conent/uploads/2018/foobar.jpg', $router);
        $parser->parseMedia($media);
    }

    public function urlProvider()
    {
        $inputUrl = '<img class="xx" src="http://wordpress.com/wp-conent/uploads/2018/foobar.jpg" />';
        $outputUrl = '/uploads/foobar.jpg';

        yield [$inputUrl, $outputUrl];
    }
}
