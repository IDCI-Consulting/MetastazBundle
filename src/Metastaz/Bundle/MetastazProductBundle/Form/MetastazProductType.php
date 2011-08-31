<?php

namespace Metastaz\Bundle\MetastazProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MetastazProductType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('short_description', null, array('label' => 'Short description'))
            ->add('long_description', null, array('label' => 'Long description'))
            ->add('category', null, array('label' => 'Template', 'required' => false))
        ;
    }

    public function getName()
    {
        return 'metastaz_bundle_metastazproductbundle_metastazproducttype';
    }
}
