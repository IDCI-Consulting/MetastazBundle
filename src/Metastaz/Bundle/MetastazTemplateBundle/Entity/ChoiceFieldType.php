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
class ChoiceFieldType extends MetastazTemplateFieldType
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
     * @var MetastazTemplateField
     */
    protected $fields;

    /**
     * @var MetastazTemplateField
     */
    protected $metastaz_template_fields;

    public function __construct()
    {
        $this->metastaz_template_fields = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add metastaz_template_field
     *
     * @param  MetastazTemplateField $MetastazTemplateField
     */
    public function addMetastazTemplateField(MetastazTemplateField $MetastazTemplateField)
    {
        $this->metastaz_template_field[] = $MetastazTemplateField;
    }

    /**
     * Get metastaz_template_fields
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMetastazTemplateFields()
    {
        return $this->metastaz_template_fields;
    }

    /**
     * Add field
     *
     * @param  MetastazTemplateField $field
     */
    public function addField(MetastazTemplateField $field)
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
