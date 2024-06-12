<?php

namespace App\Controller;

use App\Entity\Domain;
use App\Form\EditDnsRecordAnalysisType;
use App\Form\EditDomainType;
use App\Repository\DomainData\DnsRecordRepository;
use App\Entity\DomainData\DnsRecord;
use App\Repository\DomainRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'investigation_')]
#[IsGranted('ROLE_ADMIN')]
class InvestigationController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->redirectToRoute('investigation_domain_index');
    }
    #[Route('/dns', name: 'dns_index')]
    public function dns_index(DnsRecordRepository $recordRepository): Response
    {
        $dnsRecords = $recordRepository->findSorted();
        return $this->render('admin/investigation/dns_index.html.twig', [
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

        return $this->render('admin/investigation/dns_show.html.twig', [
            'record' => $record,
            'editForm' => $editForm->createView(),
        ]);
    }

    #[Route('/domain', name: 'domain_index')]
    public function domain_index(DomainRepository $domainRepository): Response {
        $domains = $domainRepository->findToAnalyse();
        return $this->render('admin/investigation/domain_index.html.twig', [
            'domains' => $domains,
        ]);
    }

    #[Route('/domain/edit/{host}', name: 'domain_edit')]
    public function domain_edit(Domain $domain, Request $request, EntityManagerInterface $em): Response
    {
        $editForm = $this->createForm(EditDomainType::class, $domain);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            return $this->redirectToRoute('investigation_domain_index');
        }

        return $this->renderForm('admin/investigation/domain_edit.html.twig', [
            'editForm' => $editForm,
            'domain' => $domain,
        ]);
    }
}
