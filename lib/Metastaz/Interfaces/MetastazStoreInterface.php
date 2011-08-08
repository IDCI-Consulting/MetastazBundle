<?php

namespace Metastaz\Interfaces;

/**
 * MetastazStore interface define operations which must be override 
 * by each concrete store.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Mirsal ENNAIME <mirsal@mirsal.fr>
 * @licence: GPL
 */
interface MetastazStoreInterface
{
    /**
     * MetastazStore constructor
     *
     * @param MetastazContainer $metastazContainer
     */
    function __construct(MetastazContainer $metastaz_container);

    /**
     * Retrieve a Metastaz
     *
     * @param string $dimension
     * @param string $namespace
     * @param string $key
     * @param string $culture
     * @return mixed
     */
    function get($dimension, $namespace, $key, $culture = null);

    /**
     * Add or update a Metastaz
     *
     * @param string $dimension
     * @param string $namespace
     * @param string $key
     * @param string $value
     * @param string $culture
     */
    function put($dimension, $namespace, $key, $value, $culture = null);

    /**
     * Retrieve all Metastaz for a given namespace
     *
     * @param string $dimension
     * @param string $namespace
     * @return array
     */
    function getAll($dimension, $namespace);

    /**
     * Remove a Metastaz
     *
     * @param string $dimension
     * @param string $namespace
     * @param string $key
     */
    function delete($dimension, $namespace, $key);
}
