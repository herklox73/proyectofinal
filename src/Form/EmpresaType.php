<?php

namespace App\Form;

use App\Entity\Empresa;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class EmpresaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombreEmpresa', TextType::class, [
                'label' => 'Nombre de la Empresa'
            ])
            ->add('ruc', TextType::class, [
                'label' => 'RUC'
            ])
            ->add('personaEncargada', TextType::class, [
                'label' => 'Persona Encargada'
            ])
            ->add('contacto', TextType::class, [
                'label' => 'Contacto'
            ])
            ->add('ubicacion', TextType::class, [
                'label' => 'Ubicación'
            ])
            ->add('direccion', TextType::class, [
                'label' => 'Dirección'
            ])
           
            ->add('email', EmailType::class, [
                'mapped' => false,  // No se mapea directamente con la entidad Empresa, ya que se usará para crear un User
                'label' => 'Correo Electrónico'
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,  // Similar al email, este campo no se mapea con la entidad Empresa
                'label' => 'Contraseña'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Empresa::class,  // Aquí se mapea la entidad a la que se asocia el formulario
        ]);
    }
}
