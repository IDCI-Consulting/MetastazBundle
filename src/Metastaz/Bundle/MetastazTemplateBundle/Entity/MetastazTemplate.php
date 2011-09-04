<?php
namespace Metastaz\Bundle\MetastazTemplateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\Container;
use Metastaz\Bundle\MetastazTemplateBundle\MetastazTemplateBundle;

/**
 * MetastazTemplate define the metadata fields that can use a metastaz object
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 * @ORM\Entity(repositoryClass="Metastaz\Bundle\MetastazTemplateBundle\Repository\MetastazTemplateRepository")
 */
class MetastazTemplate extends MetastazTemplateFieldType
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
     * @ORM\OneToMany(targetEntity="MetastazTemplateField", mappedBy="metastaz_template", cascade={"persist"})
     */
    protected $metastaz_template_fields;

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
     * Add metastaz_template_fields
     *
     * @param MetastazTemplateField $MetastazTemplateField
     */
    public function addMetastazTemplateField(MetastazTemplateField $MetastazTemplateField)
    {
        $this->metastaz_template_fields[] = $MetastazTemplateField;
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
     * @param MetastazTemplateField $field
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

    /**
     * Has field
     *
     * @param string $namespace
     * @param string $key
     * @return boolean 
     */
    public function hasField($namespace, $key)
    {
        foreach($this->getMetastazTemplateFields() as $field)
        {
            if ($field->getMetaNamespace() == $namespace &&
               $field->getMetaKey() == $key) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get form type name
     *
     * @return string
     */
    public function getFormTypeName()
    {
        return sprintf('%sMetastazTemplateType', $this->getName());
    }

    /**
     * Get an array of field types
     *
     * @return array 
     */
    public function getFormFields()
    {
        $form_fields = array();
        $fields = array();

        foreach ($this->getMetastazTemplateFields() as $field) {
            $tmp = $field->getFormField();
            $fields[$field->getMetaNamespace()][] = $tmp;
        }

        foreach($fields as $namespaceFields) {
            $form_fields = array_merge($form_fields, $namespaceFields);
        }

        return $form_fields;
    }

    /**
     * Get an array of indexed fields for a specified template
     *
     * @param string $template_name
     * @return array
     */
    public static function getIndexedFields($template_name)
    {
        $em = MetastazTemplateBundle::getContainer()->get('doctrine')->getEntityManager('metastaz_template');
        return $em->getRepository('MetastazTemplateBundle:MetastazTemplate')->getIndexedFields($template_name);
    }

    /**
     * Get an array of available templates
     *
     * @return array
     */
    public static function getTemplates()
    {
        $em = MetastazTemplateBundle::getContainer()->get('doctrine')->getEntityManager('metastaz_template');
        return $em->getRepository('MetastazTemplateBundle:MetastazTemplate')->findAll();
    }
}
