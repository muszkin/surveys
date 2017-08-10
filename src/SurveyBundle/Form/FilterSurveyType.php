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

class FilterSurveyType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filters',ChoiceType::class,[
                'choices' => $options['filters'],
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('user',EntityType::class,[
                'class' => 'SurveyBundle\Entity\User',
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control'
                ],
                "required" => FALSE,
            ])
            ->add('type',EntityType::class,[
                'class' => 'SurveyBundle\Entity\SurveyType',
                'choice_label' => 'type',
                'attr' => [
                    'class' => 'form-control'
                ],
                "required" => FALSE,
            ])
            ->add('start',DateType::class,[
                'label' => 'From',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                "required" => FALSE,
            ])
            ->add('end',DateType::class,[
                'label' => 'To',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                "required" => FALSE,
            ])
            ->add('filter',SubmitType::class,[
                'label' => 'Filter',
                'attr' => [
                    'class' => 'btn btn-info'
                ],
            ])
            ->add('limit',ChoiceType::class,[
                'label' => "Limit",
                "choices" => [
                    "10" => "10",
                    "50" => "50",
                    "100" => "100",
                    "200" => "200",
                    "All" => "99999",
                ],
                "attr" => [
                    'class' => 'form-control',
                ],
            ])
            ->setMethod("GET")
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'filters' => null,
            'translation_domain' => 'SurveyBundle',
        ]);
    }

}