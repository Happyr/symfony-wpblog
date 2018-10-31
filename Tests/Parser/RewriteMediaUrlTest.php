<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Tests\Parser;

use Happyr\WordpressBundle\Model\Media;
use Happyr\WordpressBundle\Parser\RewriteMediaUrl;
use Happyr\WordpressBundle\Service\ImageUploaderInterface;
use PHPUnit\Framework\TestCase;

class RewriteMediaUrlTest extends TestCase
{
    const TEST_URL = 'http://wordpress.com/happyr/images/georgos.jpg';

    /**
     * @dataProvider urlProvider
     */
    public function testRewrite($inputUrl)
    {
        $media = new Media();
        $media->setSourceUrl(self::TEST_URL);
        $imageUploader = $this->getMockBuilder(ImageUploaderInterface::class)
            ->setMethods(['uploadImage'])
            ->getMock();

        $parser = new RewriteMediaUrl($inputUrl, $imageUploader);
        $parser->parseMedia($media);
    }

    public function urlProvider()
    {
        $inputUrl = 'http://wordpress.com/wp-conent/uploads/2018/foobar.jpg';

        yield [$inputUrl];
    }
}
