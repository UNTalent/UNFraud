<?php

namespace App\Controller;

use App\Entity\Domain;
use App\Form\NewCheckType;
use App\Repository\AnalysisRepository;
use App\Repository\DomainRepository;
use App\Repository\ReportRepository;
use App\Service\DomainService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_domain_')]
class DomainController extends AbstractController
{
    #[Route('/', name: 'search')]
    public function search(Request $request, DomainService $checkService,
                           DomainRepository $domainRepository, ReportRepository $reportRepository,
                           EntityManagerInterface $em): Response
    {

        $newCheckForm = $this->createForm(NewCheckType::class);
        $newCheckForm->handleRequest($request);
        if ($newCheckForm->isSubmitted() && $newCheckForm->isValid()) {

            $element = $newCheckForm->get('element');
            $report = $checkService->getReport($element->getData());
            if (!$report->getDomain()) {
                $element->addError(new FormError("Invalid format"));
            }else{
                $request->getSession()->set(ComplaintController::SESSION_REPORT_VALUE, $report->getValue());
                $em->flush();
                return $this->redirectToRoute('app_domain_check', ['host' => $report->getDomain()->getHost()]);
            }
        }

        return $this->renderForm('domain/search.html.twig', [
            'newCheckForm' => $newCheckForm,
            'reportCount' => $reportRepository->getTotalCount(),
            'notLegitReportCount' => $reportRepository->getNotLegitCount(),
            'recentDangerousDomains' => $domainRepository->findRecentDangerous(),
            'recentDomains' => $domainRepository->findRecent()
        ]);
    }


    #[Route('check/{host}', name: 'check')]
    public function check(Domain $domain, ReportRepository $reportRepository): Response
    {
        return $this->renderForm('domain/show.html.twig', [
            'domain' => $domain,
            'reports' => $reportRepository->findByDomain($domain),
        ]);
    }


    #[Route('recommend', name: 'recommend')]
    public function recommend(AnalysisRepository $analysisRepository): Response
    {
        $analyses = $analysisRepository->findSafe();
        return $this->renderForm('domain/recommend.html.twig', [
            'analyses' => $analyses,
        ]);
    }


    #[Route('blocklist.txt', name: 'blocklist')]
    #[Route('adblocker.txt', name: 'adblock')]
    public function blocklist(DomainRepository $domainRepository, Request $request): Response
    {
        $useAds = str_contains($request->attributes->get('_route'), 'adblock');

        $response = $this->render('domain/blocklist.txt.twig', [
            'useAds' => $useAds,
            'domains' => $domainRepository->findDangerous(),
        ]);

        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}
