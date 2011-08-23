<?php

namespace Metastaz\Bundle\MetastazTemplateBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * MetastazFieldType
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 */
class MetastazFieldType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('meta_namespace', null, array('label' => 'Namespace'))
            ->add('meta_key', null, array('label' => 'Key'))
            ->add('is_indexed', null, array('label' => 'Indexed'))
            ->add('options', null, array('label' => 'Options'))
            ->add('metastaz_field_type', null, array('label' => 'Field type'))
            ->add('metastaz_template')
        ;
    }

    public function getName()
    {
        return 'metastaz_bundle_metastaztemplatebundle_metastazfieldtype';
    }
}
