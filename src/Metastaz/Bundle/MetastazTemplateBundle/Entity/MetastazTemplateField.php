<?php
namespace Metastaz\Bundle\MetastazTemplateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\DependencyInjection\Container;

/**
 * MetastazTemplateField define a field aggregate by a MetastazTemplate
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 * @ORM\Entity
 * @ORM\Table(name="metastaz_template_field")
 */
class MetastazTemplateField
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
    protected $meta_namespace;

    /**
     * @ORM\Column(type="string", length="128")
     * @Assert\NotBlank()
     */
    protected $meta_key;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_indexed;

    /**
     * @ORM\Column(type="text", nullable="true")
     */
    protected $options;

    /**
     * @ORM\ManyToOne(targetEntity="MetastazTemplate", inversedBy="metastaz_template_fields", cascade={"persist"})
     * @ORM\JoinColumn(name="metastaz_template_id", referencedColumnName="id", onDelete="Cascade")
     */
    protected $metastaz_template;

    /**
     * @ORM\ManyToOne(targetEntity="MetastazTemplateFieldType", inversedBy="fields", cascade={"persist"})
     * @ORM\JoinColumn(name="metastaz_type_id", referencedColumnName="id", onDelete="Cascade")
     */
    protected $metastaz_template_field_type;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setIsIndexed(true);
    }

    /**
     * To String
     *
     * @return string 
     */
    public function __toString()
    {
        return sprintf('[%s] %s %s',
            $this->getMetastazTemplate(),
            $this->getMetaNamespace(),
            $this->getMetaKey()
        );
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
     * Set meta_namespace
     *
     * @param string $metaNamespace
     */
    public function setMetaNamespace($metaNamespace)
    {
        $this->meta_namespace = Container::camelize(str_replace(' ', '_', $metaNamespace));
    }

    /**
     * Get meta_namespace
     *
     * @return string 
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
        $this->meta_key = Container::camelize(str_replace(' ', '_', $metaKey));
    }

    /**
     * Get meta_key
     *
     * @return string 
     */
    public function getMetaKey()
    {
        return $this->meta_key;
    }

    /**
     * Set is_indexed
     *
     * @param boolean $isIndexed
     */
    public function setIsIndexed($isIndexed)
    {
        $this->is_indexed = $isIndexed;
    }

    /**
     * Get is_indexed
     *
     * @return boolean 
     */
    public function getIsIndexed()
    {
        return $this->is_indexed;
    }

    /**
     * Set options
     *
     * @param string $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * Get options
     *
     * @return string 
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set metastaz_template
     *
     * @param MetastazTemplate $metastazTemplate
     */
    public function setMetastazTemplate(MetastazTemplate $metastazTemplate)
    {
        $this->metastaz_template = $metastazTemplate;
    }

    /**
     * Get metastaz_template
     *
     * @return MetastazTemplate 
     */
    public function getMetastazTemplate()
    {
        return $this->metastaz_template;
    }
    
    /**
     * Get metastaz_template_id
     *
     * @return integer
     */
    public function getMetastazTemplateId()
    {
        return $this->getMetastazTemplate()->getId();
    }

    /**
     * Set metastaz_template_field_type
     *
     * @param MetastazTemplateFieldType $MetastazTemplateFieldType
     */
    public function setMetastazTemplateFieldType(MetastazTemplateFieldType $MetastazTemplateFieldType)
    {
        $this->metastaz_template_field_type = $MetastazTemplateFieldType;
    }

    /**
     * Get metastaz_template_field_type
     *
     * @return MetastazTemplateFieldType 
     */
    public function getMetastazTemplateFieldType()
    {
        return $this->metastaz_template_field_type;
    }

    /**
     * Get form field
     *
     * @return array 
     */
    public function getFormField()
    {
        if ($this->getMetastazTemplateFieldType() instanceof MetastazTemplate) {
            $ff = array(
                '\''.$this->getMetaNamespace().'_'.$this->getMetaKey().'\'',
                'new '.$this->getMetastazTemplateFieldType()->getFormTypeName().'()'
            );
        } else {
            $ff = array(
                '\''.$this->getMetaNamespace().'_'.$this->getMetaKey().'\'',
                '\''.$this->getMetastazTemplateFieldType().'\''
            );
        }

        $options = $this->getOptions() ? $this->getOptions().', ' : '';
        if ($options != '') {
            $ff[] = 'array('.$options.')';
        }

        return $ff;
    }
}
