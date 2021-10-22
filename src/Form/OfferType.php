<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints\File;

use App\Repository\OfferRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType; 
use Symfony\Component\Form\FormBuilderInterface;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'required' => true
            ])
            ->add('file', FileType::class, [
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'text/html'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid HTML document',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Publier l\'offre'
            ])
        ;
    }
}