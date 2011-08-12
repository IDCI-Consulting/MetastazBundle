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
class MetastazTemplateFieldType extends MetastazFieldType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    public function getName()
    {
        return 'metastaz_bundle_metastaztemplatebundle_metastaztemplatefieldtype';
    }
}
