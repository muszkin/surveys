<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 16.11.16
 * Time: 09:24
 */

namespace SurveyBundle\Form;


use SurveyBundle\Entity\Survey;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SurveyType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('ROLE_ADMIN',$options['roles'])){
            $builder
                ->add('sendDate',DateTimeType::class,[
                    'label' => 'Survey send date',
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => [
                        'class' => 'form-control'
                    ],
                ])
                ->add('openDate',DateTimeType::class,[
                    'label' => 'Survey open date',
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => [
                        'class' => 'form-control'
                    ],
                ])
                ->add('voteDate',DateTimeType::class,[
                    'label' => 'Survey vote date',
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => [
                        'class' => 'form-control'
                    ],
                ])
                ->add('resendDate',DateTimeType::class,[
                    'label' => 'Survey resend date',
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => [
                        'class' => 'form-control'
                    ],
                ])
                ->add('resendOpenDate',DateTimeType::class,[
                    'label' => 'Survey resend open date',
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => [
                        'class' => 'form-control'
                    ],
                ])
                ->add('resendVoteDate',DateTimeType::class,[
                    'label' => 'Survey resend vote date',
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => [
                        'class' => 'form-control'
                    ],
                ])
                ->add('rate',IntegerType::class,[
                    'label' => 'Survey rate',
                    'attr' => [
                        'class' => 'form-control'
                    ],
                ])
                ->add('resendRate',IntegerType::class,[
                    'label' => 'Survey resend rate',
                    'attr' => [
                        'class' => 'form-control'
                    ],
                ])
                ->add('comment',TextType::class,[
                    'label' => 'Client comment',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ])
                ->add('resendComment',TextType::class,[
                    'label' => 'Client resend comment',
                    'attr' => [
                        'class' => 'form-control'
                    ],
                ])

            ;
        }



        $builder
            ->add('submit',SubmitType::class,[
                'label' => 'Save'
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Survey::class,
            "roles" => ['ROLE_USER'],
            'translation_domain' => 'SurveyBundle',
        ]);
    }

}