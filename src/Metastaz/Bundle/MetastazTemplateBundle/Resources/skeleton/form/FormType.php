<?php

namespace {{ namespace }};

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class {{ form_class }} extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        self::addMetastazFields($builder);
    }

    public static function addMetastazFields(&$builder)
    {
        $builder
        {%- for field in fields %}

            ->add({{ field | join(", ") }})

        {%- endfor %}

        ;
    }

    public function getName()
    {
        return '{{ form_type_name }}';
    }
}
