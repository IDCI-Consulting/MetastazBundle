<?php
namespace Metastaz\Bundle\MetastazTemplateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\Container;

/**
 * MetastazTemplate define the metadata fields that can use a metastaz object
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
     * @var MetastazField
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
        $this->name = Container::camelize(str_replace(' ', '_', $name));
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
     * @param MetastazField $metastazField
     */
    public function addMetastazField(MetastazField $metastazField)
    {
        $this->metastaz_fields[] = $metastazField;
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
     * @param MetastazField $field
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

    /**
     * Has field
     *
     * @param string $namespace
     * @param string $key
     * @return boolean 
     */
    public function hasField($namespace, $key)
    {
        foreach($this->getMetastazFields() as $field)
        {
            if($field->getMetaNamespace() == $namespace &&
               $field->getMetaKey() == $key) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get an array of field types
     *
     * @return array 
     */
    public function getFormFields()
    {
        $ret = array();
        foreach($this->getMetastazFields() as $field) {
            $ret[] = array(
                $field->getMetaNamespace().'_'.$field->getMetaKey(),
                $field->getMetastazFieldType()
            );
        }
        return $ret;
    }
}
