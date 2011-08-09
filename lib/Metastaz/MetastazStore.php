<?php

namespace Metastaz;

use Metastaz\MetastazContainer;

/**
 * MetastazStore abstract class define operations which must be override 
 * by each concrete Metastaz Store.
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Mirsal ENNAIME <mirsal@mirsal.fr>
 * @licence: LGPL
 */
abstract class MetastazStore
{
    private $metastaz_container;

    /**
     * Constructor
     *
     * @param MetastazContainer $metastazContainer
     */
    public function __construct(MetastazContainer $metastaz_container)
    {
        $this->setMetastazContainer($metastaz_container);
    }

    /**
     * Get the MetastazContainer
     *
     * @return MetastazContainer
     */
    protected function getMetastazContainer()
    {
        return $this->metastaz_container;
    }

    /**
     * Set the MetastazContainer
     *
     * @param MetastazContainer $metastazContainer
     */
    protected function setMetastazContainer($metastaz_container)
    {
        $this->metastaz_container = $metastaz_container;
    }

    /**
     * Retrieve a Metastaz
     *
     * @param string $dimension
     * @param string $namespace
     * @param string $key
     * @param string $culture
     * @return mixed
     */
    abstract public function get($dimension, $namespace, $key, $culture = null);

    /**
     * Add or update a Metastaz
     *
     * @param string $dimension
     * @param string $namespace
     * @param string $key
     * @param string $value
     * @param string $culture
     */
    abstract public function put($dimension, $namespace, $key, $value, $culture = null);

    /**
     * Retrieve all Metastaz for a given namespace
     *
     * @param string $dimension
     * @param string $namespace
     * @return array
     */
    abstract public function getAll($dimension, $namespace);

    /**
     * Remove a Metastaz
     *
     * @param string $dimension
     * @param string $namespace
     * @param string $key
     */
    abstract public function delete($dimension, $namespace, $key);

    /**
     * Remove all Metastaz for an object
     *
     * @param string $dimension
     */
    abstract public function deleteAll($dimension);
}
