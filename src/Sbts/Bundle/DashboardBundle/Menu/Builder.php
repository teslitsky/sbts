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

        $menu->addChild('Projects', ['route' => 'sbts_project_list']);
        $menu->addChild('Users', ['route' => 'sbts_user_list']);

        return $menu;
    }
}
