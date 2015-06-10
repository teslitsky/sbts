<?php

namespace Sbts\Bundle\UserBundle\Menu;

use Knp\Menu\FactoryInterface;

use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function userDropdown(FactoryInterface $factory, array $options)
    {
        $menu = $factory
            ->createItem('root')
            ->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

        $menu
            ->addChild('User', array('uri' => '#'))
            ->setAttribute('class', 'dropdown');

        $menu['User']
            ->addChild('Profile', array('uri' => '#'))
            ->setAttribute('class', 'dropdown-toggle')
            ->setAttribute('data-toggle', 'dropdown');

        $menu['User']->addChild('Logout', array('uri' => '#'));

        return $menu;
    }
}
