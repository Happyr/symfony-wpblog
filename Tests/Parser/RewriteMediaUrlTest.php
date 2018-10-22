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
        $router->expects($this->once())
            ->method('uploadImage')
            ->with($inputUrl)
            ->willReturn($outputUrl);

        $media = $this->getMockBuilder(Media::class)
            ->setMethods(['getSourceUrl'])
            ->getMock();
        $media->expects($this->once())
            ->method('getSourceUrl')
            ->willReturn($outputUrl);

        $parser = new RewriteMediaUrl($inputUrl, $router);
        $parser->parseMedia($media);
    }

    public function urlProvider()
    {
        $inputUrl = 'http://wordpress.com/wp-conent/uploads/2018/foobar.jpg';
        $outputUrl = '/uploads/foobar.jpg';

        yield [$inputUrl, $outputUrl];
    }
}
