<?php
namespace Metastaz\Bundle\MetastazProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MetastazProduct Parent represent a hierarchy relation throw two MetastazProducts
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Michel ROTTA <michel.r@allopneus.com>
 * @licence: LGPL
 * @ORM\Entity
 * @ORM\Table(
 *  name="metastaz_product_parent",
 *  uniqueConstraints={@ORM\UniqueConstraint(name="HIERARCHY_RELATION_UNIQUE", columns={"parent_id", "child_id"})})
 */
class MetastazProductParent
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="MetastazProduct", inversedBy="metastaz_product_parents", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="Cascade")
     */
    protected $parent;

    /**
     * @ORM\ManyToOne(targetEntity="MetastazProduct", inversedBy="metastaz_product_childs", cascade={"persist"})
     * @ORM\JoinColumn(name="child_id", referencedColumnName="id", onDelete="Cascade")
     */
    protected $child;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $quantity;

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
     * Set parent
     *
     * @param MetastazProduct $parent
     */
    public function setParent(MetastazProduct $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return MetastazProduct 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set child
     *
     * @param MetastazProduct $child
     */
    public function setChild(MetastazProduct $child)
    {
        $this->child = $child;
    }

    /**
     * Get child
     *
     * @return MetastazProduct 
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * To String
     *
     * @return string 
     */
    public function __toString()
    {
        return sprintf('%s parent of %s',
            $this->getParent()->getName(),
            $this->getChild()->getName()
        );
    }
}
