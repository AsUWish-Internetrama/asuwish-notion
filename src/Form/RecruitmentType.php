<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints\File;

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
                'required' => false
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
            ->add('postType', TextType::class, [
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'required' => true
            ])
            ->add('actualStatus', ChoiceType::class, [
                'choices'  => [
                    'En poste' => 'En poste',
                    'Disponible' => 'Disponible',
                    'En étude' => 'En étude',
                ],
            ])
            ->add('location', ChoiceType::class, [
                'choices'  => [
                    'Pas de préférence' => 'Pas de préférence',
                    'Rouen' => 'Rouen',
                    'Cean' => 'Cean',
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
                'choices'  => [
                    'Spontanée pour CDI' => 'Spontanée pour CDI',
                    'Spontanée pour alternance' => 'Spontanée pour alternance',
                    'Spontanée pour stage' => 'Spontanée pour stage'              
                ],
            ])
            ->add('file', FileType::class, [
                'mapped' => false,
                'required' => false,
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