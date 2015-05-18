<?php

namespace Sbts\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SbtsUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
