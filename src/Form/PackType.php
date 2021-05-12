<?php

namespace App\Form;

use App\Entity\Pack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


           $builder
               ->add('nom', TextType::class, [
                   'required' => false,
//                'label'=>false,
                   'attr' => [
                       'placeholder' => "Saisir le nom du pack",
                       'class' => 'inputNamePack']
               ])
               ->add('description1', TextType::class, [
                   'required' => false,
//                'label'=>false,
                   'attr' => [
                       'placeholder' => "Saisir la description du pack",
                   ]
               ])
               ->add('description2', TextType::class, [
                   'required' => false,
//                'label'=>false,
                   'attr' => [
                       'placeholder' => "Compléter la description du pack",
                   ]
               ])
               ->add('image1', FileType::class, [
                   "required" => false,
                   'constraints' => [
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
               ->add('image2', FileType::class, [
                   "required" => false,
                   'constraints' => [
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
               ->add('image3', FileType::class, [
                   "required" => false,
                   'constraints' => [
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
               ->add('prix', NumberType::class, [
                   'required' => false,
                   'attr' => [
                       'placeholder' => "Saisir le prix du pack",

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
