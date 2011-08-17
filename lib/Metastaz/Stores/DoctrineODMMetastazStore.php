<?php

namespace Metastaz\Stores;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Metastaz\Bundle\MetastazBundle\MetastazBundle;
use Metastaz\Bundle\MetastazBundle\Document\Metastaz;
use Metastaz\Interfaces\MetastazStoreInterface;

/**
 * DoctrineMetastazStore is a concrete provider to store Metastazs throw Doctrine ODM.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: LGPL
 */
class DoctrineODMMetastazStore implements MetastazStoreInterface
{
    protected static $parameters = array();
    protected static $dm         = null;

    /**
     * Get the Document Manager
     *
     * @return DocumentManager
     */
    protected static function getDocumentManager()
    {
        if(null === self::$dm)
        {
            self::$dm = MetastazBundle::getContainer()
                ->get('doctrine.odm.mongodb.'.self::$parameters['connection'].'_document_manager')
            ;
        }

        return self::$dm;
    }

    public function __construct($parameters)
    {
        self::$parameters = $parameters;
    }

    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     * @throw NotFoundHttpException
     */
    public function get($dimension, $namespace, $key, $culture = null)
    {
        $dm = self::getDocumentManager();

        $document = $dm->getRepository('MetastazBundle:Metastaz')->findOneBy(
            array(
                'meta_dimension' => $dimension,
                'meta_namespace' => $namespace,
                'meta_key' => $key
            )
        );

        if (!$document) {
            throw new NotFoundHttpException(
                sprintf('Unable to find Metastaz entity with the following parameter: %s %s %s.',
                    $dimension,
                    $namespace,
                    $key
                )
            );
        }

        //TODO: Return data in function of the culture parameter
        return $document->getMetaValue();
    }

    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     */
    public function put($dimension, $namespace, $key, $value, $culture = null)
    {
        $dm = self::getDocumentManager();
        $document = $dm->getRepository('MetastazBundle:Metastaz')->findOneBy(
            array(
                'meta_dimension' => $dimension,
                'meta_namespace' => $namespace,
                'meta_key' => $key
            )
        );

        if (!$document) {
            $document = new Metastaz();
            $document->setMetaDimension($dimension);
            $document->setMetaNamespace($namespace);
            $document->setMetaKey($key);
        }

        //TODO: Save data in function of the culture parameter
        $document->setMetaValue($value);
        $dm->persist($document);
        $dm->flush();
    }

    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     */
    public function getAll($dimension)
    {
        $dm = self::getDocumentManager();
        $documents = $dm->getRepository('MetastazBundle:Metastaz')->findBy(
            array('meta_dimension' => $dimension)
        );

        $ret = array();
        foreach($documents as $document) {
            $ret[$document->getMetaNamespace()][$document->getMetaKey()] = $document->getMetaValue();
        }

        return $ret;
    }

    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     * @throw NotFoundHttpException
     */
    public function delete($dimension, $namespace, $key)
    {
        $dm = self::getDocumentManager();
        $document = $dm->getRepository('MetastazBundle:Metastaz')->findOneBy(
            array(
                'meta_dimension' => $dimension,
                'meta_namespace' => $namespace,
                'meta_key' => $key
            )
        );

        if (!$document) {
            throw new NotFoundHttpException(
                sprintf('Unable to find Metastaz entity with the following parameter: %s %s %s.',
                    $dimension,
                    $namespace,
                    $key
                )
            );
        }

        $dm->remove($document);
        $dm->flush();
    }

    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     * @throw NotFoundHttpException
     */
    public function deleteAll($dimension)
    {
        $dm = self::getDocumentManager();
        $documents = $dm->getRepository('MetastazBundle:Metastaz')->findBy(
            array('meta_dimension' => $dimension)
        );

        foreach($documents as $document) {
            $dm->remove($document);
        }

        $dm->flush();
    }

    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     */
    public function addMany($dimension, array $metastazs)
    {
        $dm = self::getDocumentManager();

        foreach($metastazs as $namespace => $keys) {
            foreach($keys as $key => $value) {
                $document = new Metastaz();
                $document->setMetaDimension($dimension);
                $document->setMetaNamespace($namespace);
                $document->setMetaKey($key);
                $document->setMetaValue($value);
                $dm->persist($document);
            }
        }
    }

    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     */
    public function updateMany($dimension, array $metastazs)
    {
        $dm = self::getDocumentManager();

        foreach($metastazs as $namespace => $keys) {
            foreach($keys as $key => $value) {
                $document = $dm->getRepository('MetastazBundle:Metastaz')->findOneBy(
                    array(
                        'meta_dimension' => $dimension,
                        'meta_namespace' => $namespace,
                        'meta_key' => $key
                    )
                );

                if (!$document) {
                    $document = new Metastaz();
                    $document->setMetaDimension($dimension);
                    $document->setMetaNamespace($namespace);
                    $document->setMetaKey($key);
                }
                $document->setMetaValue($value);
                $dm->persist($document);
            }
        }
    }

    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     */
    public function deleteMany($dimension, array $metastazs)
    {
        $dm = self::getDocumentManager();

        foreach($metastazs as $namespace => $keys) {
            foreach($keys as $key => $value) {
                $document = $dm->getRepository('MetastazBundle:Metastaz')->findOneBy(
                    array(
                        'meta_dimension' => $dimension,
                        'meta_namespace' => $namespace,
                        'meta_key' => $key
                    )
                );
                $dm->remove($document);
            }
        }
    }
    
    /**
     * @see Metastaz\Interfaces\MetastazStoreInterface
     */
    public static function flush()
    {
        self::getDocumentManager()->flush();
    }
}
