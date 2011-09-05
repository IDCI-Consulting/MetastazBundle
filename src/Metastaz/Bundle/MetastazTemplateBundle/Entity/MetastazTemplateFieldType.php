<?php
namespace Metastaz\Bundle\MetastazTemplateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MetastazTemplateFieldType
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 * @ORM\Entity
 * @ORM\Table(
 *   name="metastaz_template_field_type",
 *   uniqueConstraints={@ORM\UniqueConstraint(name="TYPE_UNIQUE", columns={"name", "discriminator"})}
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "base" = "MetastazTemplateFieldType",
 *     "template" = "MetastazTemplate",
 *     "choice" = "ChoiceFieldType",
 *     "date_time" = "DateAndTimeFieldType",
 *     "group" = "FieldGroupType",
 *     "hidden" = "HiddenFieldType",
 *     "other" = "OtherFieldType",
 *     "text" = "TextFieldType"
 * })
 */
class MetastazTemplateFieldType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length="128")
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="MetastazTemplateField", mappedBy="metastaz_template_field_type", cascade={"persist"})
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
     * Add metastaz_template_field
     *
     * @param MetastazTemplateField $MetastazTemplateField
     */
    public function addField(MetastazTemplateField $field)
    {
        $this->fields[] = $field;
    }

    /**
     * Get metastaz_template_fields
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Get form field
     *
     * @return array 
     */
    public function getFormField(MetastazTemplateField $field)
    {
        $ff = array(
            '\''.$field->getMetaNamespace().'_'.$field->getMetaKey().'\'',
            '\''.$this.'\''
        );

        $options = $field->getOptions() ? $field->getOptions().', ' : '';
        if ($options != '') {
            $ff[] = 'array('.$options.')';
        }

        return $ff;
    }
}
