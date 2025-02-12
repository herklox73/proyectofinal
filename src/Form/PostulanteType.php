<?php

namespace App\Form;

use App\Entity\Postulante;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostulanteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombres', TextType::class, [
                'label' => 'Nombres'
            ])
            ->add('apellidos', TextType::class, [
                'label' => 'Apellidos'
            ])
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Contraseña'],
                'second_options' => ['label' => 'Repite la contraseña'],
                'mapped' => false, // No está en la entidad Postulante, sino en User
            ])
            ->add('genero', ChoiceType::class, [
                'choices'  => [
                    'Masculino' => 'Masculino',
                    'Femenino' => 'Femenino',
                ],
            ])
            ->add('edad', IntegerType::class, [
                'label' => 'Edad'
            ])
            ->add('direccion', TextType::class, [
                'label' => 'Dirección'
            ])
            ->add('cedula', TextType::class, [
                'label' => 'Cédula'
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono'
            ])
           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Postulante::class,
        ]);
    }
}
