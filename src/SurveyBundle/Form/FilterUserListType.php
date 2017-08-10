<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 10.11.16
 * Time: 13:33
 */

namespace SurveyBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterUserListType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start',DateType::class,[
                'label' => 'From',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('end',DateType::class,[
                'label' => 'To',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('filter',SubmitType::class,[
                'label' => 'Filter',
                'attr' => [
                    'class' => 'btn btn-info'
                ],
            ])
            ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'translation_domain' => 'SurveyBundle',
        ]);
    }

}