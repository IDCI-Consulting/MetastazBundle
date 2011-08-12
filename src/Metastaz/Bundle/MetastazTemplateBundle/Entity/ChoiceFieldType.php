<?php
namespace Metastaz\Bundle\MetastazTemplateBundle\Entity;

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
     * @var MetastazField
     */
    protected $fields;

    /**
     * @var MetastazField
     */
    protected $metastaz_fields;

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
     * Add metastaz_field
     *
     * @param  MetastazField $metastazField
     */
    public function addMetastazField(MetastazField $metastazField)
    {
        $this->metastaz_field[] = $metastazField;
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
     * Add field
     *
     * @param  MetastazField $field
     */
    public function addField(MetastazField $field)
    {
        $this->fields[] = $field;
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
