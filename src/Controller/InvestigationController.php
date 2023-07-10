<?php

namespace App\Controller;

use App\Repository\DomainData\DnsRecordRepository;
use App\Entity\DomainData\DnsRecord;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/investigation', name: 'investigation_')]
class InvestigationController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(DnsRecordRepository $recordRepository): Response
    {
        $dnsRecords = $recordRepository->findSorted();
        return $this->render('investigation/index.html.twig', [
            'dnsRecords' => $dnsRecords,
        ]);
    }

    #[Route('/dns/record/{id}', name: 'dns_show')]
    public function dns_show(DnsRecord $record): Response
    {
        return $this->render('investigation/dns_show.html.twig', [
            'record' => $record,
        ]);
    }
}
