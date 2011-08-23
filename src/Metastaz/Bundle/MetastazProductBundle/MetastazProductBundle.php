<?php

namespace Metastaz\Bundle\MetastazProductBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * MetastazProductBundle.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 */
class MetastazProductBundle extends Bundle
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

    public function boot()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $evm = $em->getEventManager();
        // timestampable
        $evm->addEventSubscriber(new \Gedmo\Timestampable\TimestampableListener());
    }
}
