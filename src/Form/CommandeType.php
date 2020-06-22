<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('reference')
            ->add('nom', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Nom & Prenoms", 'autocomplete'=>'off']])
            ->add('tel', TelType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Téléphone", 'autocomplete'=>'off']])
            ->add('adresse', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Adresse de livraison", 'autocomplete'=>'off']])
            ->add('email', EmailType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Adresse email"]])
            ->add('quantite', IntegerType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Quantité commandée", 'autocomplete'=>'off']])
            //->add('montant')
            //->add('flag')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
