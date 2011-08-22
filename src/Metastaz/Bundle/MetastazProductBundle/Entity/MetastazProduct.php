<?php
namespace Metastaz\Bundle\MetastazProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

use Metastaz\Interfaces\MetastazInterface;
use Metastaz\MetastazContainer;
use Metastaz\Bundle\MetastazProductBundle\Entity\MetastazProductParent;
use Metastaz\Bundle\MetastazProductBundle\Entity\MetastazProductAssociation;

/**
 * MetastazProduct represent any items or item familly
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Michel ROTTA <michel.r@allopneus.com>
 * @licence: LGPL
 * @ORM\Entity
 * @ORM\Table(name="metastaz_product")
 * //@MTZ\Entity
 */
class MetastazProduct implements MetastazInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="MetastazProductParent", mappedBy="metastaz_product", cascade={"persist"})
     */
    protected $parents;

    /**
     * @ORM\OneToMany(targetEntity="MetastazProductParent", mappedBy="metastaz_product", cascade={"persist"})
     */
    protected $childs;

    /**
     * @ORM\OneToMany(targetEntity="MetastazProductAssociation", mappedBy="metastaz_product", cascade={"persist"})
     */
    protected $associations;

    /**
     * @ORM\Column(type="string", length="255")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length="255", nullable=true)
     */
    protected $shortDescription;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $longDescription;

    /**
     * @ORM\ManyToOne(targetEntity="MetastazProductCategory", inversedBy="metastaz_products", cascade={"persist"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $created_at;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updated_at;

    /**
     * Holds MetastazContainer Objects
     */
    protected $metastaz_container = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parents = new ArrayCollection();
        $this->childs = new ArrayCollection();
        $this->associations = new ArrayCollection();
    }

    /**
     * To String
     *
     * @return string 
     */
    public function __toString()
    {
        return sprintf('%s [%s]',
            $this->getName(),
            $this->getCategory()
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
     * Set shortDescription
     *
     * @param string $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set longDescription
     *
     * @param text $longDescription
     */
    public function setLongDescription($longDescription)
    {
        $this->longDescription = $longDescription;
    }

    /**
     * Get longDescription
     *
     * @return text 
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }

    /**
     * Set category
     *
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }

    /**
     * Get created_at
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    }

    /**
     * Get updated_at
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Add parents
     *
     * @param MetastazProductParent $parents
     */
    public function addParents(MetastazProductParent $parents)
    {
        $this->parents[] = $parents;
    }

    /**
     * Get parents
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Add childs
     *
     * @param MetastazProductParent $childs
     */
    public function addChilds(MetastazProductParent $childs)
    {
        $this->childs[] = $childs;
    }

    /**
     * Get childs
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * Add associations
     *
     * @param MetastazProductAssociation $associations
     */
    public function addAssociations(MetastazProductAssociation $associations)
    {
        $this->associations[] = $associations;
    }

    /**
     * Get associations
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAssociations()
    {
        return $this->associations;
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function getMetastazDimensionId()
    {
        return $this->getId();
    }

    /**
     * Get metastaz_template_name
     *
     * @return string 
     */
    public function getMetastazTemplateName()
    {
        return $this->getCategory() ? 
            $this->getCategory()->getTemplateName() :
            '';
        ;
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function getMetastazContainer()
    {
        if(null === $this->metastaz_container)
        {
            $this->metastaz_container = new MetastazContainer(
                array(
                    'object' => $this,
//                    'container' => array(
//                        'use_template' => true,
//                        'instance_spooling' => true
//                    ),
//                    'store' => array(
//                        'class' => 'DoctrineODMMetastazStore',
//                        'parameters' => array('connection' => 'metastaz')
//                    )
                )
            );
        }
        return $this->metastaz_container;
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function getMetastaz($namespace, $key, $culture = null)
    {
        return $this->getMetastazContainer()->get($namespace, $key, $culture);
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function putMetastaz($namespace, $key, $value, $culture = null)
    {
        return $this->getMetastazContainer()->put($namespace, $key, $value, $culture);
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function deleteMetastaz($namespace, $key)
    {
        return $this->getMetastazContainer()->delete($namespace, $key);
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function getAllMetastaz()
    {
        return $this->getMetastazContainer()->getAll();
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function deleteAllMetastaz()
    {
        return $this->getMetastazContainer()->deleteAll();
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function loadMetastaz()
    {
        return $this->getMetastazContainer()->load();
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function persistMetastaz()
    {
        return $this->getMetastazContainer()->persist();
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function flushMetastaz()
    {
        return $this->getMetastazContainer()->flush();
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function getMetastazIndexes()
    {
        return $this->getMetastazContainer()->getIndexedFields();
    }
}
