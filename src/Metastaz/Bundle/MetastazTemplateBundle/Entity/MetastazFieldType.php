<?php
namespace Metastaz\Bundle\MetastazTemplateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * MetastazFieldType
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: LGPL
 * @ORM\Entity
 * @ORM\Table(
 *   name="metastaz_field_type",
 *   uniqueConstraints={@ORM\UniqueConstraint(name="TYPE_UNIQUE", columns={"name", "discriminator"})}
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "base" = "MetastazFieldType",
 *     "template" = "MetastazTemplate",
 *     "choice" = "ChoiceFieldType",
 *     "date_time" = "DateAndTimeFieldType",
 *     "group" = "FieldGroupType",
 *     "hidden" = "HiddenFieldType",
 *     "other" = "OtherFieldType",
 *     "text" = "TextFieldType"
 * })
 */
class MetastazFieldType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length="128")
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="MetastazField", mappedBy="metastaz_field_type", cascade={"persist"})
     */
    protected $fields;

    /**
     * To String
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->getName();
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

    public function __construct()
    {
        $this->fields = new ArrayCollection();
    }
    
    /**
     * Add metastaz_field
     *
     * @param MetastazField $metastazField
     */
    public function addField(MetastazField $field)
    {
        $this->fields[] = $field;
    }

    /**
     * Get metastaz_fields
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFields()
    {
        return $this->fields;
    }
}
