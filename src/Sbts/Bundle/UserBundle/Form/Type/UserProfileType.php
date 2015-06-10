<?php

namespace Sbts\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, ['disabled' => true])
            ->add('plainPassword', 'repeated', [
                'type'            => 'password',
                'options'         => ['translation_domain' => 'FOSUserBundle'],
                'first_options'   => ['label' => 'form.new_password'],
                'second_options'  => ['label' => 'form.new_password_confirmation'],
                'invalid_message' => 'fos_user.password.mismatch',
                'required'        => false,
            ])
            ->add('fullname')
            ->add('avatarFile', 'vich_file', [
                'required'     => false,
                'allow_delete' => true,
            ])
            ->remove('current_password')
            ->add('save', 'submit', ['label' => 'user.link.update_my_profile']);
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }

    public function getName()
    {
        return 'sbts_user_profile';
    }
}
