<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Twig;

use Happyr\WordpressBundle\Api\WpClient;
use Happyr\WordpressBundle\Service\Wordpress;
use Twig\Extension\AbstractExtension;

class WordpressExtension extends AbstractExtension
{
    private $wordpress;

    public function __construct(Wordpress $wp)
    {
        $this->wordpress = $wp;
    }


    public function getFunctions()
    {
        return [
           new \Twig_SimpleFunction('getWpMenu', [$this->wordpress, 'getMenu']),
           new \Twig_SimpleFunction('getWpPage', [$this->wordpress, 'getPage']),
        ];
    }

}
