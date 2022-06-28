<?php
namespace App\ParamConverter;

use App\Entity\Domain;
use App\Repository\DomainRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DomainParamConverter  implements ParamConverterInterface {


    public function __construct(private DomainRepository $domainRepository)
    {
    }

    function apply(Request $request, ParamConverter $configuration)
    {
        $host = $request->get('host');
        if($domain = $this->domainRepository->findOneByHost($host)){
            $request->attributes->set($configuration->getName(), $domain);
        }else{
            throw new NotFoundHttpException("Domain $host not found");
        }
    }

    function supports(ParamConverter $configuration)
    {
        return (
            $configuration->getClass() == Domain::class
        );
    }

}
