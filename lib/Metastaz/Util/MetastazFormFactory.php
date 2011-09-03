<?php

namespace Metastaz\Util;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Metastaz\Bundle\MetastazTemplateBundle\Entity\MetastazTemplate;
use Metastaz\Bundle\MetastazProductBundle\Form\MetastazProductWithCategoryType;
use Metastaz\Interfaces\MetastazInterface;

/**
 * MetastazFormFactory
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 */
class MetastazFormFactory
{
    public static function createForm($container, $object, array $options = array())
    {
        $name = '';
        $data = $object;

        if ($object instanceof MetastazInterface) {
            $name = $object->getMetastazTemplateName();
        } elseif ($object instanceof MetastazTemplate) {
            $name = $object->getName();
            $data = null;
        } else {
            throw new NotFoundHttpException(sprintf(
                'The given object %s doesn\'t implements MetastazInterface or isn\'t a MetastazTemplate',
                get_class($object)
            ));
        }

        $class_name = self::getFormClassName($name);

        if (!class_exists($class_name)) {
            throw new NotFoundHttpException(
                sprintf('Unable to find the following MetastazTemplateType: %s.', $class_name)
            );
        }

        $type = new $class_name();
        $form = null;
        if ($object instanceof MetastazInterface) {
            $formBuilder = $container->get('form.factory')->createBuilder(new MetastazProductWithCategoryType(), $object);
            $class_name::addMetastazFields($formBuilder);
            $form = $formBuilder->getForm();
        } elseif ($object instanceof MetastazTemplate) {
            $form = $container->get('form.factory')->create($type, $data, $options);
        }

        return $form;
    }

    public static function getFormClassName($name)
    {
        return sprintf('%s\\%sMetastazTemplateType',
            'Application\\Form',
            $name
        );
    }
}
