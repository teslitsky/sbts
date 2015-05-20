<?php

namespace Sbts\Bundle\DashboardBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory
            ->createItem('root')
            ->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('Projects', array('route' => 'sbts_project_list'));
        $menu->addChild('Issues', array('route' => 'sbts_issue_list'));
        $menu->addChild('Users', array('route' => 'sbts_user_list'));

        return $menu;
    }
}
