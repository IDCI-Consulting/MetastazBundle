<?php

namespace Metastaz\Bundle\MetastazTemplateBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MetastazTemplateType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
        ;
    }

    public function getName()
    {
        return 'metastaz_bundle_metastaztemplatebundle_metastaztemplatetype';
    }
}
