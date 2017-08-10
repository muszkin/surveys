<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 08.11.16
 * Time: 12:18
 */

namespace SurveyBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SurveyBundle\Entity\Team;

class UserType extends AbstractType
{

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
            ->add('username',TextType::class,[
                "attr" => [
                    "class" => 'form-control',
                    "aria-describedby" => "sizing-addon1",
                    "placeholder" => "Name of module for team"
                ],
            ])
            ->add('team',EntityType::class,[
                "class" => 'SurveyBundle\Entity\Team',
                "label" => "Choose team",
                "attr" => [
                    "class" => 'form-control'
                ],
            ])
            ->add('email',TextType::class,[
                "attr" => [
                    "class" => 'form-control',
                    "aria-describedby" => "sizing-addon1",
                    "placeholder" => "Name of new team"
                ],
            ])
            ->add('sid',TextType::class,[
                "attr" => [
                    "class" => 'form-control',
                    "aria-describedby" => "sizing-addon1",
                    "placeholder" => "Name of new team"
                ],
            ])
            ->add('admin_id',TextType::class,[
                "attr" => [
                    "class" => 'form-control',
                    "aria-describedby" => "sizing-addon1",
                    "placeholder" => "Name of new team"
                ],
            ])
            ->add('password',PasswordType::class,[
                "attr" => [
                    "class" => 'form-control',
                    "aria-describedby" => "sizing-addon1",
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
            'data_class' => 'SurveyBundle\Entity\User',
            'translation_domain' => 'SurveyBundle',
        ));
    }
}