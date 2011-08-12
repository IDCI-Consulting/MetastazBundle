<?php
namespace Metastaz\Bundle\MetastazBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Metastaz is a concrete store for metadata.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Michel ROTTA <michel.r@allopneus.com>
 * @licence: GPL
 * @MongoDB\Document
 * 
 */
class Metastaz
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * Dimension
     *
     * @MongoDB\String
     */
    protected $meta_dimension;

    /**
     * Namespace
     *
     * @MongoDB\String
     */
    protected $meta_namespace;

    /**
     * Key
     *
     * @MongoDB\String
     */
    protected $meta_key;

    /**
     * Value
     *
     * @MongoDB\String
     */
    protected $meta_value;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set meta_dimension
     *
     * @param string $metaDimension
     */
    public function setMetaDimension($metaDimension)
    {
        $this->meta_dimension = $metaDimension;
    }

    /**
     * Get meta_dimension
     *
     * @return string $metaDimension
     */
    public function getMetaDimension()
    {
        return $this->meta_dimension;
    }

    /**
     * Set meta_namespace
     *
     * @param string $metaNamespace
     */
    public function setMetaNamespace($metaNamespace)
    {
        $this->meta_namespace = $metaNamespace;
    }

    /**
     * Get meta_namespace
     *
     * @return string $metaNamespace
     */
    public function getMetaNamespace()
    {
        return $this->meta_namespace;
    }

    /**
     * Set meta_key
     *
     * @param string $metaKey
     */
    public function setMetaKey($metaKey)
    {
        $this->meta_key = $metaKey;
    }

    /**
     * Get meta_key
     *
     * @return string $metaKey
     */
    public function getMetaKey()
    {
        return $this->meta_key;
    }

    /**
     * Set meta_value
     *
     * @param string $metaValue
     */
    public function setMetaValue($metaValue)
    {
        $this->meta_value = $metaValue;
    }

    /**
     * Get meta_value
     *
     * @return string $metaValue
     */
    public function getMetaValue()
    {
        return $this->meta_value;
    }
}
