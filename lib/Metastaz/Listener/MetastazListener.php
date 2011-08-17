<?php

namespace Metastaz\Listener;

use Metastaz\MetastazContainer;
use Metastaz\Interfaces\MetastazInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class MetastazListener
{
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if ($entity instanceof MetastazInterface) {
            $entity->persistMetastaz();
        }
    }
    
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if ($entity instanceof MetastazInterface) {
            $entity->deleteAllMetastaz();
        }
    }
}