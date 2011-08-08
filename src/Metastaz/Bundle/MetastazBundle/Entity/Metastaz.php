<?php
// src/Hevea/Bundle/MetastazBundle/Entity/Metastaz.php
namespace Hevea\Bundle\MetastazBundle\Entity;

/**
 * Metastaz is a concrete store for metadata.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Michel ROTTA <michel.r@allopneus.com>
 * @licence: LGPL
 */
class Metastaz
{
    /**
     * Id
     */
    protected $id;

    /**
     * Dimension
     */
    protected $meta_dimension;

    /**
     * Namespace
     */
    protected $meta_namespace;

    /**
     * Key
     */
    protected $meta_key;

    /**
     * Value
     */
    protected $meta_value;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dimension
     *
     * @param string $dimension
     */
    public function setMetaDimension($dimension)
    {
        $this->meta_dimension = $dimension;
    }

    /**
     * Get dimension
     *
     * @return string 
     */
    public function getMetaDimension()
    {
        return $this->meta_dimension;
    }

    /**
     * Set namespace
     *
     * @param string $namespace
     */
    public function setMetaNamespace($namespace)
    {
        $this->meta_namespace = $namespace;
    }

    /**
     * Get namespace
     *
     * @return string 
     */
    public function getMetaNamespace()
    {
        return $this->meta_namespace;
    }

    /**
     * Set key
     *
     * @param string $key
     */
    public function setMetaKey($key)
    {
        $this->meta_key = $key;
    }

    /**
     * Get key
     *
     * @return string 
     */
    public function getMetaKey()
    {
        return $this->meta_key;
    }

    /**
     * Set value
     *
     * @param text $value
     */
    public function setMetaValue($value)
    {
        $this->meta_value = $value;
    }

    /**
     * Get value
     *
     * @return text 
     */
    public function getMetaValue()
    {
        return $this->meta_value;
    }
}
