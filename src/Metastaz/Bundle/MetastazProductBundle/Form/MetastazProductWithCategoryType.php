<?php

namespace Metastaz\Bundle\MetastazProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Metastaz\Bundle\MetastazProductBundle\Entity\MetastazProductCategory;
use Metastaz\Bundle\MetastazTemplateBundle\Entity\MetastazTemplate;

class MetastazProductWithCategoryType extends MetastazProductType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('association', 'collection', array(
            'type' => new MetastazProductType(),
            'allow_add' => true
        ));

        $categoryNames = $templateNames = array();
        foreach(MetastazProductCategory::getCategories() as $category)
        {
            $categoryNames[] = $category->getTemplateName();
        }
        foreach(MetastazTemplate::getTemplates() as $template)
        {
            $templateNames[] = $template->getName();
        }

        $suggestions = array_diff($templateNames, $categoryNames);

        if(!empty($suggestions)) {
            $builder->add('categorySuggestion', 'choice', array(
                'label' => 'Suggestion',
                'choices' => array_combine($suggestions, $suggestions),
                'required' => false,
                'property_path' => false,
            ));
        }
    }

    public function getName()
    {
        return 'metastaz_bundle_metastazproductbundle_metastazproductwithcategorytype';
    }
}

