<?php

namespace Metastaz\Util;

use Symfony\Component\Form\Util\PropertyPath;

/**
 * MetastazForm
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 */
class MetastazPropertyPath extends PropertyPath
{
    /**
     * Reads the value of the property at the given index in the path
     *
     * @param  object $object         The object to read from
     * @param  integer $currentIndex  The index of the read property in the path
     * @return mixed                  The value of the property
     */
    protected function readProperty($object, $currentIndex)
    {
        die("read");
    }

    /**
     * Sets the value of the property at the given index in the path
     *
     * @param object  $objectOrArray The object or array to traverse
     * @param integer $currentIndex  The index of the modified property in the
     *                               path
     * @param mixed $value           The value to set
     */
    protected function writeProperty(&$objectOrArray, $currentIndex, $value)
    {
        die("write");
    }
}

