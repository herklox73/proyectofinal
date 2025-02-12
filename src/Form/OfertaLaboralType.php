<?php

namespace App\Form;

use App\Entity\OfertaLaboral;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfertaLaboralType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cargo', TextType::class, ['label' => 'Cargo'])
            ->add('tipo_contacto', ChoiceType::class, [
                'label' => 'Tipo de contrato',
                'choices' => [
                    'Indefinido' => 'Indefinido',
                    'Temporal' => 'Temporal',
                    'Freelance' => 'Freelance',
                ]
            ])
            ->add('canton', TextType::class, ['label' => 'Cantón'])
            ->add('parroquia', TextType::class, ['label' => 'Parroquia'])
            ->add('remuneracion', NumberType::class, ['label' => 'Remuneración'])
            ->add('jornada', ChoiceType::class, [
                'label' => 'Jornada de trabajo',
                'choices' => [
                    'Tiempo completo' => 'Tiempo completo',
                    'Medio tiempo' => 'Medio tiempo',
                    'Por horas' => 'Por horas',
                ]
            ])
            ->add('area_estudios', TextType::class, ['label' => 'Área de estudios'])
            ->add('contacto', TextType::class, ['label' => 'Contacto'])
            ->add('publicar', SubmitType::class, ['label' => 'Publicar oferta']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OfertaLaboral::class,
        ]);
    }
}
