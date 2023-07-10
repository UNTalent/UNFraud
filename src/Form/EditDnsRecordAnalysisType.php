<?php

namespace App\Form;

use App\Entity\DomainData\DnsRecord;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditDnsRecordAnalysisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('applyAnalysis', options: [
                'label' => 'Apply the following analysis',
                'placeholder' => 'No analysis',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DnsRecord::class,
        ]);
    }
}
