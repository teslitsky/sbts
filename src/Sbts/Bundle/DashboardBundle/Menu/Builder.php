<?php

namespace Sbts\Bundle\DashboardBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav navbar-nav',
            ),
        ));

        $menu->addChild('Projects', array('route' => 'sbts_project_list'));
        $menu->addChild('Profile', array('route' => 'fos_user_profile_show'));
        $menu->addChild('Login', array('route' => 'fos_user_security_login'));
        $menu->addChild('Logout', array('route' => 'fos_user_security_logout'));

        return $menu;
    }
}
