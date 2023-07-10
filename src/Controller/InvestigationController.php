<?php

namespace App\Controller;

use App\Form\EditDnsRecordAnalysisType;
use App\Repository\DomainData\DnsRecordRepository;
use App\Entity\DomainData\DnsRecord;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function dns_show(DnsRecord $record, Request $request, EntityManagerInterface $em): Response
    {
        $editForm = $this->createForm(EditDnsRecordAnalysisType::class, $record);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            return $this->redirectToRoute('investigation_dns_show', ['id' => $record->getId()]);
        }

        return $this->render('investigation/dns_show.html.twig', [
            'record' => $record,
            'editForm' => $editForm->createView(),
        ]);
    }
}
