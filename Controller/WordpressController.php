<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Controller;

use Happyr\WordpressBundle\Service\Wordpress;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Simple controller for index and page
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class WordpressController extends Controller
{
    private $wordpress;
    private $indexTemplate;
    private $pageTemplate;

    public function __construct(Wordpress $wordpress, string $indexTemplate, string $pageTemplate)
    {
        $this->wordpress = $wordpress;
        $this->indexTemplate = $indexTemplate;
        $this->pageTemplate = $pageTemplate;
    }

    public function index()
    {
        $pages = $this->wordpress->listPosts();

        return $this->render($this->indexTemplate, ['pages'=>$pages]);
    }

    public function show($slug)
    {
        if (null === $page = $this->wordpress->getPage($slug)) {
            throw $this->createNotFoundException();
        }

        return $this->render($this->pageTemplate, ['page'=>$page]);
    }
}
