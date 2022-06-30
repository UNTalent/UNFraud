<?php

namespace App\Controller;

use App\Repository\DomainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_sitemap_')]
class SitemapController extends AbstractController
{

    #[Route('sitemap.xml', name: 'domains')]
    public function domains(DomainRepository $domainRepository): Response
    {
        return $this->renderForm('sitemap/domains.xml.twig', [
            'domains' => $domainRepository->findActive(),
        ]);
    }

}
