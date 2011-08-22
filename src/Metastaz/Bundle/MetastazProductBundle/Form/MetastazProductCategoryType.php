<?php

namespace Metastaz\Bundle\MetastazProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MetastazProductCategoryType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('template_name')
        ;
    }

    public function getName()
    {
        return 'metastaz_bundle_metastazproductbundle_metastazproductcategorytype';
    }
}
