<?php

declare(strict_types=1);

namespace Happyr\WordpressBundle\Controller;

use Happyr\WordpressBundle\Service\Wordpress;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Simple controller for index and page.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class WordpressController extends AbstractController
{
    private $wordpress;
    private $indexTemplate;
    private $pageTemplate;
    private $allowInvalidate;

    public function __construct(Wordpress $wordpress, string $indexTemplate, string $pageTemplate, bool $allowInvalidate)
    {
        $this->wordpress = $wordpress;
        $this->indexTemplate = $indexTemplate;
        $this->pageTemplate = $pageTemplate;
        $this->allowInvalidate = $allowInvalidate;
    }

    public function index()
    {
        $pages = $this->wordpress->listPosts();

        return $this->render($this->indexTemplate, ['pages' => $pages]);
    }

    public function show($slug)
    {
        if (null === $page = $this->wordpress->getPage($slug)) {
            throw $this->createNotFoundException();
        }

        return $this->render($this->pageTemplate, ['page' => $page]);
    }

    public function invalidate($slug)
    {
        $this->wordpress->purgeCache($slug);

        return new Response('', 204);
    }
}
