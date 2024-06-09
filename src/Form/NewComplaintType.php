<?php

namespace App\Form;

use App\Entity\Complaint\Complaint;
use App\Repository\Complaint\ComplaintStatusRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewComplaintType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('element', ReportElementType::class, options: [
                'label' => 'What do you want to report?',
                'attr' => [
                    'placeholder' => 'scammer@example.com, fakejobs.example.com, etc.',
                ],
            ])
            ->add('status', options: [
                'label' => 'Which of the following best describes your situation?',
                'required' => true,
                'expanded' => true,
                'query_builder' => fn(ComplaintStatusRepository $repository) => $repository->getOrderedQueryBuilder(),
            ])
            ->add('country', CountryType::class, options: [
                'label' => 'What is your citizenship or residency?',
                'required' => true,
                'placeholder' => 'Choose a country',
                'help' => 'To know which laws apply to your case'
            ])
            ->add('email', options: [
                'label' => 'What is your email address?',
                'required' => true,
                'help' => "So we can contact you in case of an investigation"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Complaint::class,
        ]);
    }
}
