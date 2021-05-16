<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class,[
                "label"=>'Pseudo',
                "required"=>false,
                "attr"=>[
                    "placeholder"=>'Veuillez saisir votre pseudo']
            ])

            ->add('password', PasswordType::class, [
                "label"=>'Mot de passe',
                "attr"=> [
                    "placeholder"=>"Veuillez saisir votre mot de passe"
                ]
            ])
            ->add('email', EmailType::class, [
                "required"=> false,
                "label"=>'Email',
                "attr"=> [
                    "placeholder"=>"Veuillez saisir votre email"
                ]]
            )
            ->add('nom',TextType::class,[
                "label"=>'Nom',
                "required"=>false,
                "attr"=>[
                    "placeholder"=>'Veuillez saisir votre nom']
            ])
            ->add('prenom',TextType::class,[
                "label"=>'Prénom',
                "required"=>false,
                "attr"=>[
                    "placeholder"=>'Veuillez saisir votre prénom']
            ])
            ->add('confirm_password',PasswordType::class,[
                "label"=>'Confirmation de mot de passe',
                "attr"=>[
                    "placeholder"=>'Confirmez votre mot de passe']
            ])

            ->add('birthday', BirthdayType::class, ['widget' => 'choice','placeholder'=>['day'=>'Jour','month'=>'Mois', 'year'=>'Année'] ,'format' => 'dd-MM-yyyy', 'html5'=>false, 'label'=>'Date de naissance'])

            ->add('adresse',TextType::class,[
                "label"=>'Adresse',
                "required"=>false,
                "attr"=>[
                    "placeholder"=>'Veuillez saisir le n° et la rue']
            ])

            ->add('cp',NumberType::class, [
                "label"=>'Code Postal',
                "required"=>false,
                "attr"=>[
                    "placeholder"=>'Veuillez saisir le code postal']
            ])

            ->add('ville',TextType::class,[
                "label"=>'Ville',
                "required"=>false,
                "attr"=>[
                    "placeholder"=>'Veuillez saisir la ville']
            ])

            ->add('telephone',NumberType::class, [
                "label"=>'téléphone',
                "required"=>false,
                "attr"=>[
                    "placeholder"=>'Veuillez saisir votre numéro de téléphone']
            ])

            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
