<?php

namespace SurveyBundle\Form;

use SurveyBundle\Entity\SurveyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'attr' => [
                    'placeholder' => 'Name of rate (visible to clients)',
                    'class' => 'form-control'
                ],
            ])
            ->add('value',IntegerType::class,[
                'attr' => [
                    'placeholder' => 'Value of rate',
                    'class' => 'form-control'
                ],
            ])
            ->add('survey_type',EntityType::class,[
                'class' => SurveyType::class,
                'attr' => [
                    'placeholder' => 'Rate for survey type ?',
                    'class' => 'form-control'
                ],
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SurveyBundle\Entity\Rate',
            'translation_domain' => 'SurveyBundle',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'surveybundle_rate';
    }


}
