<?php

namespace App\Controller;

use App\Entity\Complaint\Complaint;
use App\Entity\Complaint\ComplaintReport;
use App\Form\NewComplaintType;
use App\Service\DomainService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/report', name: 'complaint_')]
class ComplaintController extends AbstractController
{

    const SESSION_REPORT_VALUE = 'reportValue';

    #[Route('/new', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em, DomainService $domainService): Response
    {

        $complaint = new Complaint();
        $complaintForm = $this->createForm(NewComplaintType::class, $complaint, [
            'default_report_value' => $request->getSession()->get(self::SESSION_REPORT_VALUE, null)
        ]);
        $complaintForm->handleRequest($request);

        if ($complaintForm->isSubmitted() && $complaintForm->isValid()) {

            $element = $complaintForm->get('element');
            $report = $domainService->getReport($element->getData());

            if($report->isSafe()){
                $element->addError(new FormError("This is not a fraudulent element."));
            }else{

                $request->getSession()->remove(self::SESSION_REPORT_VALUE);
                $complaintReport = new ComplaintReport($complaint, $report);

                $em->persist($complaintReport);
                $em->flush();

                return $this->redirectToRoute('complaint_edit', [
                    'id' => $complaint->getId(),
                    'code' => $complaint->getCode()
                ]);


            }
        }

        return $this->renderForm('complaint/index.html.twig', [
            'complaintForm' => $complaintForm,
        ]);
    }


    #[Route('/{id}/{code}/edit', name: 'edit')]
    public function edit(Complaint $complaint, $code): Response
    {
        if($complaint->getCode() !== $code) {
            throw $this->createNotFoundException('The code is not valid');
        }

        return $this->renderForm('complaint/show.html.twig', [
            'complaint' => $complaint,
        ]);
    }
}
