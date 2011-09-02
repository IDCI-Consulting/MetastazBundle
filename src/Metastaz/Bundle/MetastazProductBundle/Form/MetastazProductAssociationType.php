<?php

namespace Metastaz\Bundle\MetastazProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MetastazProductAssociationType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('priority')
            ->add('product')
            ->add('product_association')
        ;
    }

    public function getName()
    {
        return 'metastaz_product_association_type';
    }
}
