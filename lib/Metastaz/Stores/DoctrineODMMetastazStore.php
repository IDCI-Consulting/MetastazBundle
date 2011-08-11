<?php

namespace Metastaz\Stores;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Metastaz\Bundle\MetastazBundle\MetastazBundle;
use Metastaz\Bundle\MetastazBundle\Document\Metastaz;
use Metastaz\MetastazStore;

/**
 * DoctrineMetastazStore is a concrete provider to store Metastazs throw 
 * Doctrine ODM.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: LGPL
 */
class DoctrineODMMetastazStore extends MetastazStore
{
    protected $dm = null;

    /**
     * Get the Entity Manager
     *
     * 
     */
    protected function getDocumentManager()
    {
        if(!$this->em)
        {
            $params = $this->getMetastazContainer()->getParameter('metastaz.store_parameters');
            
            $this->em = MetastazBundle::getContainer()
                ->get('doctrine.odm.mongodb.document_manager')               
            ;
        }
       
        return $this->em;
    }

    /**
     * @see Hevea\Bundle\MetastazBundle\Stores\MetastazStore
     * @throw NotFoundHttpException
     */
    public function get($dimension, $namespace, $key, $culture = null)
    {
        $dm = $this->getDocumentManager();
        
        $entity = $dm->getRepository('MetastazBundle:Metastaz')->findOneBy(
            array(
                'meta_dimension' => $dimension,
                'meta_namespace' => $namespace,
                'meta_key' => $key
            )
        );

        if (!$entity) {
            throw new NotFoundHttpException(
                sprintf('Unable to find Metastaz entity with the following parameter: %s %s %s.',
                    $dimension,
                    $namespace,
                    $key
                )
            );
        }

        //TODO: Return data in function of the culture parameter   
        return $entity->getMetaValue();
    }

    /**
     * @see Hevea\Bundle\MetastazBundle\Stores\MetastazStore
     */
    public function put($dimension, $namespace, $key, $value, $culture = null)
    {               
        $dm = $this->getDocumentManager();
        $entity = $dm->getRepository('MetastazBundle:Metastaz')->findOneBy(
            array(
                'meta_dimension' => $dimension,
                'meta_namespace' => $namespace,
                'meta_key' => $key
            )
        );              

        if (!$entity) {
            $entity = new Metastaz();
            $entity->setMetaDimension($dimension);
            $entity->setMetaNamespace($namespace);
            $entity->setMetaKey($key);
        }

        //TODO: Save data in function of the culture parameter
        $entity->setMetaValue($value);
        $dm->persist($entity);        
        $dm->flush();
    }

    /**
     * @see Hevea\Bundle\MetastazBundle\Stores\MetastazStore
     */
    public function getAll($dimension, $namespace)
    {
        $dm = $this->getDocumentManager();
        $entities = $dm->getRepository('MetastazBundle:Metastaz')->findBy(
            array(
                'meta_dimension' => $dimension,
                'meta_namespace' => $namespace
            )
        );

        $ret = array();
        foreach($entities as $entity) {
            $ret[$entity->getMetaKey()] = $entity->getMetaValue();
        }
        return $ret;
    }

    /**
     * @see Hevea\Bundle\MetastazBundle\Stores\MetastazStore
     * @throw NotFoundHttpException
     */
    public function delete($dimension, $namespace, $key)
    {
        $dm = $this->getDocumentManager();
        $entity = $dm->getRepository('MetastazBundle:Metastaz')->findOneBy(
            array(
                'meta_dimension' => $dimension,
                'meta_namespace' => $namespace,
                'meta_key' => $key
            )
        );

        if (!$entity) {
            throw new NotFoundHttpException(
                sprintf('Unable to find Metastaz entity with the following parameter: %s %s %s.',
                    $dimension,
                    $namespace,
                    $key
                )
            );
        }

        $dm->remove($entity);
        $dm->flush();
    }

    /**
     * @see Hevea\Bundle\MetastazBundle\Stores\MetastazStore
     * @throw NotFoundHttpException
     */
    public function deleteAll($dimension)
    {
        $dm = $this->getDocumentManager();
        $entities = $dm->getRepository('MetastazBundle:Metastaz')->findBy(
            array('meta_dimension' => $dimension)
        );

        foreach($entities as $entity) {
            $dm->remove($entity);
        }
        $dm->flush();
    }
}
