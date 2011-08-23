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
            ->add('shortDescription', null, array('label' => 'Short description'))
            ->add('longDescription', null, array('label' => 'Long description'))
            ->add('category')
        ;
    }

    public function getName()
    {
        return 'metastaz_bundle_metastazproductbundle_metastazproducttype';
    }
}
