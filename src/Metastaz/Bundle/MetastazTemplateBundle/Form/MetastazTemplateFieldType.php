<?php

namespace Metastaz\Bundle\MetastazTemplateBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * MetastazTemplateFieldType
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 */
class MetastazTemplateFieldType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('meta_namespace', null, array('label' => 'Namespace'))
            ->add('meta_key', null, array('label' => 'Key'))
            ->add('is_indexed', null, array('label' => 'Indexed', 'required' => false))
            ->add('options', null, array('label' => 'Options'))
            ->add('metastaz_template_field_type', null, array('label' => 'Field type'))
            ->add('metastaz_template')
        ;
    }

    public function getName()
    {
        return 'metastaz_template_field_type';
    }
}
