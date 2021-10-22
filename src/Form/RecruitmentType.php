<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints\File;

use App\Repository\OfferRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType; 
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class RecruitmentType extends AbstractType
{
    protected $_offers = [
        'Spontanée pour CDI' => 'Spontanée pour CDI',
        'Spontanée pour alternance' => 'Spontanée pour alternance',
        'Spontanée pour stage' => 'Spontanée pour stage'
    ];

    public function __construct(
        OfferRepository $offerRepository
    ) {
        $this->_offerRepository = $offerRepository;

        foreach ($this->_offerRepository->findAll() as $offer) {
            if ($offer->getTitle()) {
                $this->_offers[$offer->getTitle()] = $offer->getTitle();
            }
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true
            ])
            ->add('lastName', TextType::class, [
                'required' => true
            ])
            ->add('phoneNumber', TextType::class, [
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'required' => true
            ])
            ->add('website', UrlType::class, [
                'required' => false
            ])
            ->add('linkedin', UrlType::class, [
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'required' => true
            ])
            ->add('actualStatus', ChoiceType::class, [
                'choices'  => [
                    'En poste' => 'En poste',
                    'Disponible' => 'Disponible',
                    'Étudiant' => 'Étudiant',
                ],
            ])
            ->add('location', ChoiceType::class, [
                'choices'  => [
                    'Pas de préférence' => 'Pas de préférence',
                    'Rouen' => 'Rouen',
                    'Caen' => 'Caen',
                    'Rennes' => 'Rennes'
                ],
            ])
            ->add('agencies', ChoiceType::class, [
                'choices'  => [
                    'Pas de préférence' => 'Pas de préférence',
                    'Asuwish' => 'Asuwish',
                    'Arcange' => 'Arcange',
                    'Aurore Boréale' => 'Aurore Boréale',
                    'Bangarang' => 'Bangarang',
                    'BBird' => 'BBired',
                    'Casus Belli' => 'Casus Belli',
                    'Internetrama' => 'Internetrama',
                    'Klub Cean' => 'Klub Cean',
                    'Klub Rennes' => 'Klub Rennes',
                    'No Filter Media' => 'No Filter Media',
                    'Web Interactive' => 'Web Interactive'                    
                ],
            ])
            ->add('candidateType', ChoiceType::class, [
                'choices'  => $this->_offers,
            ])
            ->add('file', FileType::class, [
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer ma candidature'
            ])
        ;
    }
}