<?php

namespace Metastaz\Stores;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Metastaz\Bundle\MetastazBundle\MetastazBundle;
use Metastaz\Bundle\MetastazBundle\Entity\Metastaz;
use Metastaz\Interfaces\MetastazStoreInterface;
use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\Mapping\Driver\YamlDriver;

/**
 * DoctrineORMMetastazStore is a concrete provider to store Metastazs throw 
 * Doctrine ORM.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: LGPL
 */
class DoctrineORMMetastazStore implements MetastazStoreInterface
{
    protected static $parameters = array();
    protected static $em         = null;

    /**
     * Get the Entity Manager
     *
     * @return EntityManager
     */
    protected static function getEntityManager()
    {
        if(null === self::$em)
        {
            self::$em = MetastazBundle::getContainer()
                ->get('doctrine')
                ->getEntityManager(self::$parameters['connection'])
            ;
        }

        return self::$em;
    }

    public function __construct($parameters)
    {
        if(empty(self::$parameters)) {
            self::$parameters = $parameters;
        }
    }

    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     */
    public function get($dimension, $namespace, $key, $culture = null)
    {
        $em = self::getEntityManager();
        $entity = $em->getRepository('MetastazBundle:Metastaz')->findOneBy(
            array(
                'meta_dimension' => $dimension,
                'meta_namespace' => $namespace,
                'meta_key' => $key
            )
        );

        if (!$entity) {
            return null;
        }

        //TODO: Return data in function of the culture parameter
        return self::_deserialize($entity->getMetaValue());
    }

    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     */
    public function put($dimension, $namespace, $key, $value, $culture = null)
    {
        $em = self::getEntityManager();
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
     * @see Metastaz\Interfaces\MetastazStoreInterface
     */
    public function getAll($dimension)
    {
        $em = self::getEntityManager();
        $entities = $em->getRepository('MetastazBundle:Metastaz')->findBy(
            array(
                'meta_dimension' => $dimension
            )
        );

        $ret = array();
        foreach($entities as $entity) {
            $ret[$entity->getMetaNamespace()][$entity->getMetaKey()] = self::_deserialize($entity->getMetaValue());
        }

        return $ret;
    }

    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     * @throw NotFoundHttpException
     */
    public function delete($dimension, $namespace, $key)
    {
        $em = self::getEntityManager();
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
     * @see Metastaz\Interfaces\MetastazStoreInterface
     * @throw NotFoundHttpException
     */
    public function deleteAll($dimension)
    {
        $em = self::getEntityManager();
        $entities = $em->getRepository('MetastazBundle:Metastaz')->findBy(
            array('meta_dimension' => $dimension)
        );

        foreach($entities as $entity) {
            $em->remove($entity);
        }

        $em->flush();
    }

    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     */
    public function addMany($dimension, array $metastazs)
    {
        $em = self::getEntityManager();

        foreach($metastazs as $namespace => $keys) {
            foreach($keys as $key => $value) {
                $entity = new Metastaz();
                $entity->setMetaDimension($dimension);
                $entity->setMetaNamespace($namespace);
                $entity->setMetaKey($key);
                $entity->setMetaValue(self::_serialize($value));
                $em->persist($entity);
            }
        }
    }

    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     */
    public function updateMany($dimension, array $metastazs)
    {
        $em = self::getEntityManager();

        foreach($metastazs as $namespace => $keys) {
            foreach($keys as $key => $value) {
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
                $entity->setMetaValue(self::_serialize($value));
                $em->persist($entity);
            }
        }
    }

    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     */
    public function deleteMany($dimension, array $metastazs)
    {
        $em = self::getEntityManager();

        foreach($metastazs as $namespace => $keys) {
            foreach($keys as $key => $value) {
                $entity = $em->getRepository('MetastazBundle:Metastaz')->findOneBy(
                    array(
                        'meta_dimension' => $dimension,
                        'meta_namespace' => $namespace,
                        'meta_key' => $key
                    )
                );
                $em->remove($entity);
            }
        }
    }
    
    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     */
    public static function flush()
    {
        self::getEntityManager()->flush();
    }

    /**
     * Serialize
     */
    protected static function _serialize($data)
    {
        return serialize($data);
    }

    /**
     * Deserialize
     */
    protected static function _deserialize($data)
    {
        return unserialize($data);
    }
}
