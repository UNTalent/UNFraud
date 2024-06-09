<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Url;

class ReportElementType extends AbstractType
{

    public function getParent(): string
    {
        return UrlType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => "Enter an email address or an url",
            'attr' => [
                'placeholder' => "https://unops.org, hr@unicef.org, ..."
            ],
            'mapped' => false,
            'required' => true,
            'constraints' => [
                new AtLeastOneOf([
                    new Email(),
                    new Url()
                ])
            ]
        ]);
    }
}
