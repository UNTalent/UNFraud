<?php

namespace App\Controller;

use App\Repository\RatingRepository;
use App\Service\DomainService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractFOSRestController
{

    #[Route('/check/{source}', name: 'check')]
    public function check($source, DomainService $domainService): Response
    {
        $report = $domainService->getReport($source, save: false);

        // No flush when reported by API

        return $this->handleView($this->view($report, 200));

    }

    #[Route('/ratings', name: 'ratings')]
    public function ratings(RatingRepository $ratingRepository): Response
    {
        return $this->handleView($this->view([
            'ratings' => $ratingRepository->findAll()
        ], 200));
    }

}
