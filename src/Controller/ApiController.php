<?php

namespace App\Controller;

use App\Entity\Domain;
use App\Form\NewCheckType;
use App\Repository\DomainRepository;
use App\Repository\ReportRepository;
use App\Service\DomainService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
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
}
