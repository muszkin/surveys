<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 14.11.16
 * Time: 09:57
 */

namespace SurveyBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReplyType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rate',ChoiceType::class,[
                'label' => 'Is my respone was helpful / understood ?',
                'choices' => [
                    'Yes' => 1,
                    'No' => 0,
                ],
                'expanded' => true,
            ])
            ->add('comment',TextareaType::class,[
                'label' => 'Be briefly what influenced your decision',
                'attr' => [
                    'rows' => 4,
                    'styles' => 'width:100%; font-weight:400;'
                ],
                "required" => false,
            ])
            ->add('submit',SubmitType::class,[
                "label" => 'Send vote',
                "attr" => [
                    'class' => 'button',
                    'style' => 'width: 29%; float: right; margin-top: 40px',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SurveyBundle\Entity\Survey',
            'translation_domain' => 'SurveyBundle',
        ));
    }
}