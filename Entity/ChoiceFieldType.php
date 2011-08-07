<?php
namespace Bundle\MetastazBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChoiceFieldType
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 * @ORM\Entity
 */
class ChoiceFieldType extends MetastazFieldType
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
     * @var Bundle\MetastazBundle\Entity\MetastazField
     */
    protected $metastaz_fields;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->metastaz_fields = new \Doctrine\Common\Collections\ArrayCollection();
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
}
