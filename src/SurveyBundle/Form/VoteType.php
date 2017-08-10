<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 14.11.16
 * Time: 09:57
 */

namespace SurveyBundle\Form;

use Doctrine\ORM\EntityRepository;
use SurveyBundle\Entity\Rate;
use SurveyBundle\Entity\SurveyStaffRate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoteType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ($options['data']->getSurveyType()->isExtraQuestions()){
            $builder->add('staffRate',EntityType::class,[
                'class' => SurveyStaffRate::class,
                'label' => 'Is another contact should be handled by the same consultant?',
                'expanded' => true,
                'translation_domain' => 'SurveyBundle'
            ]);
        }
        if ($options['data']->getSurveyType()->getId() == 3){
            $builder->add('rate',EntityType::class,[
                'class' => Rate::class,
                'label' => 'Is my respone on phone was helpful ?',
                'expanded' => true,
                "query_builder" => function(EntityRepository $er) use($options){
                    return $er
                        ->createQueryBuilder('u')
                        ->where('u.survey_type = '.$options['data']
                                ->getSurveyType()
                                ->getId());
                },
            ]);
        }else{
            $builder->add('rate',EntityType::class,[
                    'class' => Rate::class,
                    'label' => 'Is my respone was helpful / understood ?',
                    'expanded' => true,
                    "query_builder" => function(EntityRepository $er) use($options){
                        return $er
                            ->createQueryBuilder('u')
                            ->where('u.survey_type = '.$options['data']
                                    ->getSurveyType()
                                    ->getId());
                    },
                ]);
        }
        $builder
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