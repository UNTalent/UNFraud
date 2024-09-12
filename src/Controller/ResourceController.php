<?php

namespace App\Controller;

use App\Entity\Resource\Resource;
use App\Entity\Resource\ResourceCollection;
use App\Repository\Post\PostRepository;
use App\Repository\Resource\ResourceCollectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/resource', name: 'resource_')]
class ResourceController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(ResourceCollectionRepository $collectionRepository, PostRepository $postRepository): Response
    {
        return $this->render('resource/index.html.twig', [
            'posts' => $postRepository->findActive(),
            'collections' => $collectionRepository->findAll(),
        ]);
    }

    #[Route('s/list/{id}', name: 'collection')]
    public function collection(ResourceCollection $collection): Response
    {
        return $this->render('resource/collection.html.twig', [
            'collection' => $collection,
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Resource $resource): Response
    {
        if(! $url = $resource->getUrl()) {
            throw $this->createNotFoundException();
        }

        $response = $this->redirect($url);
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $response;
    }
}
