<?php

namespace App\Form;

use App\Entity\Postulacion;
use App\Entity\Postulante;
use App\Entity\OfertaLaboral;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostulacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha_postulacion', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('estado', ChoiceType::class, [
                'choices' => [
                    'Pendiente' => 'pendiente',
                    'Aceptado' => 'aceptado',
                    'Rechazado' => 'rechazado',
                ],
            ])
            ->add('postulante', EntityType::class, [
                'class' => Postulante::class,
                'choice_label' => 'nombres', // O el campo que prefieras
            ])
            ->add('oferta', EntityType::class, [
                'class' => OfertaLaboral::class,
                'choice_label' => 'id', // Se cambió de 'titulo' a 'id' u otro campo disponible en la entidad
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Postulacion::class,
        ]);
    }
}
