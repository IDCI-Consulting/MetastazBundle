<?php

namespace Metastaz\Listener;

use Metastaz\MetastazContainer;
use Metastaz\Interfaces\MetastazInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Metastaz interface define operations which must be override 
 * by each Metastaz object.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Emmanuel VELLA <emmanuel.v@allopneus.com>
 * @licence: GPL
 */
class MetastazListener
{
    /**
     * postPersist
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if ($entity instanceof MetastazInterface) {
            $entity->persistMetastaz();
        }
    }

    /**
     * postRemove
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if ($entity instanceof MetastazInterface) {
            $entity->deleteAllMetastaz();
        }
    }
}
