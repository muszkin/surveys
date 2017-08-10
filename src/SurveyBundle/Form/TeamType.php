<?php

namespace SurveyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                "attr" => [
                    "class" => 'form-control',
                    "aria-describedby" => "sizing-addon1",
                    "placeholder" => "Name of new team"
                ],
            ])
            ->add('module',TextType::class,[
                "attr" => [
                    "class" => 'form-control',
                    "aria-describedby" => "sizing-addon1",
                    "placeholder" => "Name of module for team"
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
            'data_class' => 'SurveyBundle\Entity\Team',
            'translation_domain' => 'SurveyBundle',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'surveybundle_team';
    }


}
