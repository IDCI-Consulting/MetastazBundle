<?php

namespace Metastaz\Bundle\MetastazProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MetastazProductParentType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('quantity')
            ->add('parent')
            ->add('child')
        ;
    }

    public function getName()
    {
        return 'metastaz_product_parent_type';
    }
}
