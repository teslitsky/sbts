<?php

namespace Sbts\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserCreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password', 'repeated', [
                'type'           => 'password',
                'required'       => true,
                'first_options'  => ['label' => 'user.form.password'],
                'second_options' => ['label' => 'user.form.repeat_password'],
            ])
            ->add('email', 'email')
            ->add('fullname')
            ->add('roles', 'choice', [
                'choices'    => [
                    'ROLE_ADMIN'    => 'user.role.admin',
                    'ROLE_MANAGER'  => 'user.role.manager',
                    'ROLE_OPERATOR' => 'user.role.operator'
                ],
                'label'      => 'user.role.list',
                'expanded'   => true,
                'multiple'   => true,
                'mapped'     => true,
            ])
            ->add('avatarFile', 'vich_file', [
                'required'     => false,
                'allow_delete' => true,
            ])
            ->add('save', 'submit', ['label' => 'user.link.create']);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sbts\Bundle\UserBundle\Entity\User',
        ]);
    }

    public function getName()
    {
        return 'sbts_user_create';
    }
}
