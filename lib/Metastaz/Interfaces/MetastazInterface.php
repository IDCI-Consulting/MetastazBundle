<?php

namespace Metastaz\Interfaces;

/**
 * Metastaz interface define operations which must be override 
 * by each Metastaz object.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 */
interface MetastazInterface
{
    function getMetastazDimension();
    function getMetastazTemplateName();
    function getMetastazContainer();
    function getMetastaz($namespace, $key, $culture = null);
    function putMetastaz($namespace, $key, $value, $culture = null);
    function getAllMetastaz($namespace);
    function deleteMetastaz($namespace, $key);
}
