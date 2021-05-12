<?php

namespace App\Form;

use App\Entity\Pack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ModifPackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('description1')
            ->add('description2')
            ->add('prix', NumberType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => "Saisir le prix du pack",

                ]
            ])
            ->add('image1File', FileType::class, [
                "required"=>false,
                'constraints'=> [
                    new File([
                        'mimeTypes' => [
                            "image/png",
                            "image/jpg",
                            "image/jpeg",
                        ],
                        'mimeTypesMessage' => "Extensions Autorisées : PNG JPG JPEG"
                    ])
                ]
            ])
            ->add('image2File', FileType::class,[
        "required"=>false,
        'constraints'=> [
            new File([
                'mimeTypes' => [
                    "image/png",
                    "image/jpg",
                    "image/jpeg",
                ],
                'mimeTypesMessage' => "Extensions Autorisées : PNG JPG JPEG"
            ])
        ]
    ])->add('image3File', FileType::class,[
                "required"=>false,
                'constraints'=> [
                    new File([
                        'mimeTypes' => [
                            "image/png",
                            "image/jpg",
                            "image/jpeg",
                        ],
                        'mimeTypesMessage' => "Extensions Autorisées : PNG JPG JPEG"
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pack::class,
        ]);
    }
}
