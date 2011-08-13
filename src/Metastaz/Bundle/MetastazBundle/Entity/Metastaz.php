<?php
namespace Metastaz\Bundle\MetastazBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Metastaz is a concrete store for metadata.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Michel ROTTA <michel.r@allopneus.com>
 * @licence: GPL
 * @ORM\Entity
 * @ORM\Table(
 *  name="metastaz",
 *  uniqueConstraints={@ORM\UniqueConstraint(name="UNIQUE_KEY", columns={"meta_dimension", "meta_namespace", "meta_key"})})
 */
class Metastaz
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Dimension
     *
     * @ORM\Column(type="string", length="255")
     */
    protected $meta_dimension;

    /**
     * Namespace
     *
     * @ORM\Column(type="string", length="128")
     */
    protected $meta_namespace;

    /**
     * Key
     *
     * @ORM\Column(type="string", length="128")
     */
    protected $meta_key;

    /**
     * Value
     *
     * @ORM\Column(type="text")
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
