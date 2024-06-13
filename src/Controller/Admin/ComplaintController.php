<?php

namespace App\Controller\Admin;

use App\Repository\Complaint\ComplaintRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/complaint', name: 'admin_complaint_')]
#[IsGranted('ROLE_ADMIN')]
class ComplaintController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ComplaintRepository $complaintRepository): Response
    {
        $complaints = $complaintRepository->findAllByDate();
        return $this->render('admin/complaint/index.html.twig', [
            'complaints' => $complaints,
        ]);
    }
}
