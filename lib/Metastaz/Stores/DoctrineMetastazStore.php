<?php

namespace Metastaz\Stores;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Metastaz\Bundle\MetastazBundle\MetastazBundle;
use Metastaz\Bundle\MetastazBundle\Entity\Metastaz;
use Metastaz\MetastazStore;
use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\Mapping\Driver\YamlDriver;

/**
 * DoctrineMetastazStore is a concrete provider to store Metastazs throw 
 * Doctrine ORM.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: LGPL
 */
class DoctrineMetastazStore extends MetastazStore
{
    protected $em = null;

    /**
     * Get the Entity Manager
     *
     * 
     */
    protected function getEntityManager()
    {
        if(!$this->em)
        {
            $params = $this->getMetastazContainer()->getParameter('metastaz.store_parameters');
            $this->em = MetastazBundle::getContainer()
                ->get('doctrine')
                ->getEntityManager($params['connexion_name'])
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
        $em = $this->getEntityManager();
        $entity = $em->getRepository('MetastazBundle:Metastaz')->findOneBy(
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
        return self::_deserialize($entity->getMetaValue());
    }

    /**
     * @see Hevea\Bundle\MetastazBundle\Stores\MetastazStore
     */
    public function put($dimension, $namespace, $key, $value, $culture = null)
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository('MetastazBundle:Metastaz')->findOneBy(
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
        $entity->setMetaValue(self::_serialize($value));
        $em->persist($entity);
        $em->flush();
    }

    /**
     * @see Hevea\Bundle\MetastazBundle\Stores\MetastazStore
     */
    public function getAll($dimension, $namespace)
    {
        $em = $this->getEntityManager();
        $entities = $em->getRepository('MetastazBundle:Metastaz')->findBy(
            array(
                'meta_dimension' => $dimension,
                'meta_namespace' => $namespace
            )
        );

        $ret = array();
        foreach($entities as $entity) {
            $ret[$entity->getMetaKey()] = self::_deserialize($entity->getMetaValue());
        }
        return $ret;
    }

    /**
     * @see Hevea\Bundle\MetastazBundle\Stores\MetastazStore
     * @throw NotFoundHttpException
     */
    public function delete($dimension, $namespace, $key)
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository('MetastazBundle:Metastaz')->findOneBy(
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

        $em->remove($entity);
        $em->flush();
    }

    /**
     * @see Hevea\Bundle\MetastazBundle\Stores\MetastazStore
     * @throw NotFoundHttpException
     */
    public function deleteAll($dimension)
    {
        $em = $this->getEntityManager();
        $entities = $em->getRepository('MetastazBundle:Metastaz')->findBy(
            array('meta_dimension' => $dimension)
        );

        foreach($entities as $entity) {
            $em->remove($entity);
        }
        $em->flush();
    }

    /**
     * @see Hevea\Bundle\MetastazBundle\Stores\MetastazStore
     */
    protected static function _serialize($data)
    {
        return json_encode($data);
    }

    /**
     * @see Hevea\Bundle\MetastazBundle\Stores\MetastazStore
     */
    protected static function _deserialize($data)
    {
        return json_decode($data);
    }
}
