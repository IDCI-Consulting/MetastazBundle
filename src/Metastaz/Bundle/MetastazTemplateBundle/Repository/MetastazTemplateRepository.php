<?php

namespace Metastaz\Bundle\MetastazTemplateBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MetastazTemplateRepository
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 */
class MetastazTemplateRepository extends EntityRepository
{
    /**
     * getIndexedFields
     *
     * @param string $name
     * @return array
     */
    public function getIndexedFields($name)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT f, ft FROM MetastazTemplateBundle:MetastazField f
                JOIN f.metastaz_field_type ft
                JOIN f.metastaz_template t
                WHERE f.is_indexed = true AND t.name = :name'
            );
        $query->setParameters(array('name' => $name));

        return $query->getResult();
    }
}

