<?php

namespace Sbts\Bundle\IssueBundle\Form\Type;

use Sbts\Bundle\IssueBundle\Entity\Resolution;
use Sbts\Bundle\IssueBundle\Entity\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class IssueFormType extends AbstractType
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->securityContext->getToken()->getUser();

        $builder
            ->add('summary')
            ->add('description');

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            $this->preSetDataBody()
        );

        $builder
            ->add('priority', 'entity', [
                'class'    => 'SbtsIssueBundle:Priority',
                'property' => 'name',
            ])
            ->add('status', 'entity', [
                'class'    => 'SbtsIssueBundle:Status',
                'property' => 'name',
            ])
            ->add('resolution', 'entity', [
                'class'    => 'SbtsIssueBundle:Resolution',
                'property' => 'name',
            ])
            ->add('reporter', 'entity', [
                'class'    => 'SbtsUserBundle:User',
                'property' => 'fullname',
                'data'     => (null === $builder->getData()->getReporter()) ? $user : $builder->getData()->getReporter()
            ])
            ->add('assignee', 'entity', [
                'class'    => 'SbtsUserBundle:User',
                'property' => 'fullname',
                'data'     => (null === $builder->getData()->getAssignee()) ? $user : $builder->getData()->getAssignee()
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $issue = $event->getData();
            $builder = $event->getForm();

            if (!$issue || null === $issue->getId()) {
                $builder->add('save', 'submit', [
                    'label' => 'issue.form.create'
                ]);
            } else {
                $builder->add('save', 'submit', [
                    'label' => 'issue.form.update'
                ]);
            }
        });
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sbts\Bundle\IssueBundle\Entity\Issue'
        ]);
    }

    /**
     *
     * @return callable
     */
    public function preSetDataBody()
    {
        return function (FormEvent $event) {
            $issue = $event->getData();
            $builder = $event->getForm();

            if ((null === $issue->getId()) || Type::TYPE_STORY !== $issue->getType()->getName()) {
                $builder->add('type', 'entity', [
                    'class'         => 'SbtsIssueBundle:Type',
                    'property'      => 'name',
                    'query_builder' => function ($builder) {
                        return $builder->createQueryBuilder('t')
                            ->where('t.name != :name')
                            ->setParameter('name', Type::TYPE_SUB_TASK);
                    },
                    'multiple'      => false,
                    'expanded'      => false,
                ]);
            }
        };
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sbts_issue_form';
    }
}
