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
     * Get the Document Manager
     *
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        if(!$this->dm)
        {
            $store = $this->getMetastazContainer()->getParameter('store');
            $this->dm = MetastazBundle::getContainer()
                ->get('doctrine.odm.mongodb.'.$store['parameters']['connection'].'_document_manager')
            ;
        }

        return $this->dm;
    }

    /**
     * @see Hevea\Bundle\MetastazBundle\Stores\MetastazStore
     * @throw NotFoundHttpException
     */
    public function get($dimension, $namespace, $key, $culture = null)
    {
        $dm = $this->getDocumentManager();

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
     * @see Hevea\Bundle\MetastazBundle\Stores\MetastazStore
     */
    public function put($dimension, $namespace, $key, $value, $culture = null)
    {
        $dm = $this->getDocumentManager();
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
     * @see Hevea\Bundle\MetastazBundle\Stores\MetastazStore
     */
    public function getAll($dimension, $namespace)
    {
        $dm = $this->getDocumentManager();
        $documents = $dm->getRepository('MetastazBundle:Metastaz')->findBy(
            array(
                'meta_dimension' => $dimension,
                'meta_namespace' => $namespace
            )
        );

        $ret = array();
        foreach($documents as $document) {
            $ret[$document->getMetaKey()] = $document->getMetaValue();
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
     * @see Hevea\Bundle\MetastazBundle\Stores\MetastazStore
     * @throw NotFoundHttpException
     */
    public function deleteAll($dimension)
    {
        $dm = $this->getDocumentManager();
        $documents = $dm->getRepository('MetastazBundle:Metastaz')->findBy(
            array('meta_dimension' => $dimension)
        );

        foreach($documents as $document) {
            $dm->remove($document);
        }
        $dm->flush();
    }
}
