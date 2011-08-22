<?php
namespace Metastaz\Bundle\MetastazProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\Container;

/**
 * MetastazProduct Category just qualify a MetastazProduct
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Michel ROTTA <michel.r@allopneus.com>
 * @licence: LGPL
 * @ORM\Entity
 * @ORM\Table(name="metastaz_product_category")
 */
class MetastazProductCategory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="MetastazProduct", mappedBy="metastaz_product_category", cascade={"persist"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length="255")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length="255", nullable="true")
     */
    protected $template_name;

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
     * Set template_name
     *
     * @param string $templateName
     */
    public function setTemplateName($templateName)
    {
        $this->template_name = Container::camelize(str_replace(' ', '_', $templateName));
    }

    /**
     * Get template_name
     *
     * @return string 
     */
    public function getTemplateName()
    {
        return $this->template_name;
    }

    /**
     * To String
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->getName();
    }
}
