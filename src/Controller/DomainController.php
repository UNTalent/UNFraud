<?php

namespace App\Controller;

use App\Entity\Domain;
use App\Form\NewCheckType;
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
    public function search(Request $request, DomainService $checkService, EntityManagerInterface $em): Response
    {

        $newCheckForm = $this->createForm(NewCheckType::class);
        $newCheckForm->handleRequest($request);
        if($newCheckForm->isSubmitted() && $newCheckForm->isValid()){

            $element = $newCheckForm->get('element');
            $domain = $checkService->getDomain($element->getData());
            if(! $domain){
                $element->addError(new FormError("Invalid format"));
                //$newCheckForm->addError(new FormError("Please enter a website, a page or an email address.", null, null, null, $element));
            }else{
                $em->flush();
                return $this->redirectToRoute('app_domain_check', ['host' => $domain->getHost()]);
            }
        }

        return $this->renderForm('domain/search.html.twig', [
            'newCheckForm' => $newCheckForm,
        ]);
    }


    #[Route('check/{host}', name: 'check')]
    public function check(Domain $domain): Response
    {
        return $this->renderForm('domain/show.html.twig', [
            'domain' => $domain,
        ]);
    }
}
