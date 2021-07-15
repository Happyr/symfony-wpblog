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
        $media = new Media(['source_url' => $inputUrl, 'id' => 'abc', 'link' => 'http://wordpress.com/media/abc.png', 'slug' => 'abc1']);
        $imageUploader = $this->getMockBuilder(ImageUploaderInterface::class)
            ->onlyMethods(['uploadImage'])
            ->getMock();

        $imageUploader->method('uploadImage')->willReturn($outputUrl);

        $parser = new RewriteMediaUrl($inputUrl, $imageUploader);
        $parser->parseMedia($media);

        $this->assertEquals($outputUrl, $media->getSourceUrl());
    }

    public function urlProvider()
    {
        $inputUrl = 'http://wordpress.com/wp-conent/uploads/2018/foobar.jpg';
        $malformedUrl = 'htp:/wordpress.com/wp-conent/uploads/2018/foobar.jpg';

        yield [$inputUrl, 'https://www.happyr.com/images/foobar.jpg'];
        yield [$malformedUrl, $malformedUrl];
    }
}
