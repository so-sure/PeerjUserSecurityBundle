<?php

/*
 * This file is part of the Peerj UserSecurityBundle
 *
 * (c) Peerj <https://peerj.com/>
 *
 * Available on github <http://www.github.com/Peerj/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Peerj\UserSecurityBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 *
 * @author Patrick McAndrew <patrick@peerj.com>
 * @version 1.0
 */
class PeerjUserSecurityBundle extends Bundle
{		
    /**
     *
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

}
