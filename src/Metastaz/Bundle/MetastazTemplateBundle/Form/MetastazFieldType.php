<?php

namespace Metastaz\Bundle\MetastazTemplateBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MetastazFieldType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('meta_namespace')
            ->add('meta_key')
            ->add('is_indexed')
            ->add('options')
            ->add('metastaz_template')
            ->add('metastaz_field_type')
        ;
    }

    public function getName()
    {
        return 'metastaz_bundle_metastaztemplatebundle_metastazfieldtype';
    }
}
