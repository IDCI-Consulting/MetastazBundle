<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Metastaz\DataMapper;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\Util\VirtualFormAwareIterator;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class MetastazPropertyPathMapper extends PropertyPathMapper
{
    public function mapDataToForm($data, FormInterface $form)
    {
        if (!empty($data)) {
            if ($form->getAttribute('property_path') !== null) {
                $form->setData($form->getAttribute('property_path')->getValue($data));
            }
        }
    }

    public function mapFormToData(FormInterface $form, &$data)
    {
        if ($form->getAttribute('property_path') !== null && $form->isSynchronized()) {
                var_dump($form->getAttribute('property_path'));
            $propertyPath = $form->getAttribute('property_path');

            // If the data is identical to the value in $data, we are
            // dealing with a reference
            $isReference = $form->getData() === $propertyPath->getValue($data);
            $byReference = $form->getAttribute('by_reference');

            if (!(is_object($data) && $isReference && $byReference)) {
                $propertyPath->setValue($data, $form->getData());
            }
        }
    }
}
