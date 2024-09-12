<?php

namespace App\Controller;

use App\Repository\DomainRepository;
use App\Repository\Post\PostRepository;
use App\Repository\Resource\ResourceCollectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_sitemap_')]
class SitemapController extends AbstractController
{

    #[Route('sitemap.xml', name: 'domains')]
    public function domains(DomainRepository $domainRepository, PostRepository $postRepository, ResourceCollectionRepository $collectionRepository): Response
    {
        $response = $this->render('sitemap/domains.xml.twig', [
            'domains' => $domainRepository->findActive(),
            'posts' => $postRepository->findActive(),
            'collections' => $collectionRepository->findAll(),
        ]);

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }

}
