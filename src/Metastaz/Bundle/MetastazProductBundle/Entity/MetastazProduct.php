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
    protected $short_description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $long_description;

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
    public function setShortDescription($short_description)
    {
        $this->short_description = $short_description;
    }

    /**
     * Get shortDescription
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->short_description;
    }

    /**
     * Set longDescription
     *
     * @param text $longDescription
     */
    public function setLongDescription($long_description)
    {
        $this->long_description = $long_description;
    }

    /**
     * Get longDescription
     *
     * @return text
     */
    public function getLongDescription()
    {
        return $this->long_description;
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
     * Add associated product
     *
     * @param MetastazProduct $product
     * @param integer $priority
     */
    public function addAssociatedProduct(MetastazProduct $product, $priority = null)
    {
        $association = new MetastazProductAssociation();
        $association->setProduct($this);
        $association->setProductAssociation($product);
        $association->setPriority($priority);
        $this->addAssociations($association);
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
     * Get metastaz_template_fields
     *
     * @return array
     */
    public function getMetastazTemplateFields()
    {
        return $this->getMetastazContainer()->getMetastazTemplateFields();
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

    public function __get($name)
    {
        if (false === strpos($name, '_')) {
            return null;
        }

        list($namespace, $key) = explode('_', $name);
        return $this->getMetastaz($namespace, $key);
    }

    public function __set($name, $value)
    {
        if (false === strpos($name, '_')) {
            return;
        }

        list($namespace, $key) = explode('_', $name);
        $this->putMetastaz($namespace, $key, $value);
    }

    /**
     * Get an unique dimension metadata array.
     *
     * @return array
     */
    public function getIndexedMetadata()
    {
        $indexedMetadata = array();
        foreach ($this->getMetastazIndexes() as $field) {
            $ns  = $field->getMetaNamespace();
            $key = $field->getMetaKey();
            $indexedMetadata[sprintf('%s_%s', $ns, $key)] = $this->getMetastaz($ns, $key);
        }

        return $indexedMetadata;
    }
}
