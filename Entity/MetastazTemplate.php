<?php
namespace Bundle\MetastazBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * MetastazTemplate define the metadata fields that can use a Metastaz object
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 * @ORM\Entity
 */
class MetastazTemplate extends MetastazFieldType
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var Bundle\MetastazBundle\Entity\MetastazField
     */
    protected $fields;

    /**
     * @ORM\OneToMany(targetEntity="MetastazField", mappedBy="metastaz_template", cascade={"persist"})
     */
    protected $metastaz_fields;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fields = new ArrayCollection();
    }


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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add metastaz_fields
     *
     * @param Bundle\MetastazBundle\Entity\MetastazField $metastazFields
     */
    public function addMetastazFields(Bundle\MetastazBundle\Entity\MetastazField $metastazFields)
    {
        $this->metastaz_fields[] = $metastazFields;
    }

    /**
     * Get metastaz_fields
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMetastazFields()
    {
        return $this->metastaz_fields;
    }

    /**
     * Add fields
     *
     * @param Bundle\MetastazBundle\Entity\MetastazField $fields
     */
    public function addFields(Bundle\MetastazBundle\Entity\MetastazField $fields)
    {
        $this->fields[] = $fields;
    }

    /**
     * Get fields
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Has field
     *
     * @param string $namespace
     * @param string $key
     * @return boolean 
     */
    public function hasField($namespace, $key)
    {
        foreach($this->getFields() as $field)
        {
            if($field->getMetaNamespace() == $namespace &&
               $field->getMetaKey() == $key) {
                return true;
            }
        }
        return false;
    }
}
