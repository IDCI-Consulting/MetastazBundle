<?php
namespace Metastaz\Bundle\MetastazProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MetastazProduct Association represent a link relation throw two MetastazProducts
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Michel ROTTA <michel.r@allopneus.com>
 * @licence: LGPL
 * @ORM\Entity
 * @ORM\Table(
 *  name="metastaz_product_association",
 *  uniqueConstraints={@ORM\UniqueConstraint(name="ASSOCIATION_RELATION_UNIQUE", columns={"product_id", "product_association_id"})})
 */
class MetastazProductAssociation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="MetastazProduct", inversedBy="metastaz_product_associations", cascade={"persist"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="Cascade")
     */
    protected $product;

    /**
     * @ORM\ManyToOne(targetEntity="MetastazProduct", inversedBy="metastaz_product_associations", cascade={"persist"})
     * @ORM\JoinColumn(name="product_association_id", referencedColumnName="id", onDelete="Cascade")
     */
    protected $product_association;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $priority;

    /**
     * To String
     *
     * @return string 
     */
    public function __toString()
    {
        return sprintf('%s associated with %s',
            $this->getProduct()->getName(),
            $this->getProductAssociation()->getName()
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
     * Set product
     *
     * @param MetastazProduct $product
     */
    public function setProduct(MetastazProduct $product)
    {
        $this->product = $product;
    }

    /**
     * Get product
     *
     * @return MetastazProduct 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set product_association
     *
     * @param MetastazProduct $productAssociation
     */
    public function setProductAssociation(MetastazProduct $productAssociation)
    {
        $this->product_association = $productAssociation;
    }

    /**
     * Get product_association
     *
     * @return MetastazProduct 
     */
    public function getProductAssociation()
    {
        return $this->product_association;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
