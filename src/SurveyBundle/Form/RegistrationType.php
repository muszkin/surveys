<?php
/**
 * Created by PhpStorm.
 * User: muszkin
 * Date: 03.11.16
 * Time: 15:19
 */

namespace SurveyBundle\Form;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'attr' => [
                    "placeholder" => 'Full name of user',
                    "class" => "form-control"
                ]
            ])
            ->add('username',TextType::class,[
                'attr' => [
                    "placeholder" => 'Login',
                    "class" => "form-control"
                ]
            ])
            ->add('email',TextType::class,[
                'attr' => [
                    "placeholder" => 'E-mail',
                    "class" => "form-control"
                ]
            ])
            ->add('sid',TextType::class,[
                'attr' => [
                    "placeholder" => 'Sid from Kayako',
                    "class" => "form-control"
                ]
            ])
            ->add('admin_id',TextType::class,[
                'attr' => [
                    "placeholder" => 'Admin id from admin.shoper.pl',
                    "class" => "form-control"
                ]
            ])
            ->add('team',EntityType::class,[
                'class' => 'SurveyBundle\Entity\Team',
                'label' => 'Choose team',
                'choice_label' => 'name',
                'required' => true,
                "attr" => [
                    "class" => 'form-control'
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        "placeholder" => 'Password',
                        "class" => "form-control"
                    ],
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        "placeholder" => 'Password confirmation',
                        "class" => "form-control"
                    ],
                ],
                'invalid_message' => 'fos_user.password.mismatch',
                'attr' => [
                    "placeholder" => 'Password',
                    "class" => "form-control"
                ]
            ])
        ;

    }


    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

}