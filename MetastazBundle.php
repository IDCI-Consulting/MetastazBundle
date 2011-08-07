<?php

namespace Bundle\MetastazBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * MetastazBundle.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Mirsal ENNAIME <mirsal@mirsal.fr>
 * @author:  Michel ROTTA <michel.r@allopneus.com>
 * @licence: GPL
 */
class MetastazBundle extends Bundle
{
    protected static $containerInstance = null;

    public function setContainer(ContainerInterface $container = null)
    {
       parent::setContainer($container);
       self::$containerInstance = $container;
    }

    public static function getContainer()
    {
      return self::$containerInstance;
    }
}
