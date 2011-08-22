<?php

namespace Metastaz\Bundle\MetastazProductBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * MetastazProductBundle.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 */
class MetastazProductBundle extends Bundle
{
    public function boot()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $evm = $em->getEventManager();
        // timestampable
        $evm->addEventSubscriber(new \Gedmo\Timestampable\TimestampableListener());
    }
}
