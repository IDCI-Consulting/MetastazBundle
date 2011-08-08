<?php
namespace Metastaz\Bundle\MetastazTemplateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MetastazField define a field aggregate by a MetastazTemplate
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: LGPL
 * @ORM\Entity
 * @ORM\Table(name="metastaz_field")
 */
class MetastazField
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
    protected $meta_namespace;

    /**
     * @ORM\Column(type="string", length="128")
     */
    protected $meta_key;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_indexed;

    /**
     * @ORM\Column(type="string", length="255", nullable="true")
     */
    protected $options;

    /**
     * @ORM\ManyToOne(targetEntity="MetastazTemplate", inversedBy="metastaz_fields", cascade={"persist"})
     * @ORM\JoinColumn(name="metastaz_template_id", referencedColumnName="id", onDelete="Cascade")
     */
    protected $metastaz_template;

    /**
     * @ORM\ManyToOne(targetEntity="MetastazFieldType", inversedBy="fields", cascade={"persist"})
     * @ORM\JoinColumn(name="metastaz_type_id", referencedColumnName="id", onDelete="Cascade")
     */
    protected $metastaz_field_type;

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
        $this->meta_namespace = $metaNamespace;
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
        $this->meta_key = $metaKey;
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
     * Set metastaz_field_type
     *
     * @param MetastazFieldType $metastazFieldType
     */
    public function setMetastazFieldType(MetastazFieldType $metastazFieldType)
    {
        $this->metastaz_field_type = $metastazFieldType;
    }

    /**
     * Get metastaz_field_type
     *
     * @return MetastazFieldType 
     */
    public function getMetastazFieldType()
    {
        return $this->metastaz_field_type;
    }
}
