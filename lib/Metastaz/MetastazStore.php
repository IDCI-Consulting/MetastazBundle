<?php

namespace Metastaz;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * Is loaded
     */
    protected $is_loaded = false;

    /**
     * Pool
     */
    static protected $pool = array();

    /**
     * Insert pool
     */
    static protected $insertPool = array();

    /**
     * Update pool
     */
    static protected $updatePool = array();

    /**
     * Delete pool
     */
    static protected $deletePool = array();

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
     * Remove a Metastaz
     *
     * @param string $dimension
     * @param string $namespace
     * @param string $key
     */
    abstract public function delete($dimension, $namespace, $key);

    /**
     * Retrieve all Metastaz for a given object dimension
     *
     * @param string $dimension
     */
    abstract public function getAll($dimension);

    /**
     * Remove all Metastaz related to an object (match with the object dimension)
     *
     * @param string $dimension
     */
    abstract public function deleteAll($dimension);

    /**
     * Add or update metastazs
     *
     * $metastazs must respect this structure:
     *
     * array(
     *   'namespace0' => array(
     *      'key0A'       => 'value0A'
     *      'key0B'       => 'value0B'
     *   ),
     *   'namespace1' => array(
     *      'key1A'       => 'value1A'
     *      'key2A'       => 'value1B'
     *   ),
     * )
     *
     * @param string $dimension
     * @param array $metastazs
     */
    abstract public function putMany($dimension, array $metastazs);

    /**
     * Delete metastazs
     *
     * $metastazs must respect this structure:
     *
     * array(
     *   'namespace0' => array(
     *      'key0A'       => 'value0A'
     *      'key0B'       => 'value0B'
     *   ),
     *   'namespace1' => array(
     *      'key1A'       => 'value1A'
     *      'key2A'       => 'value1B'
     *   ),
     * )
     *
     * @param string $dimension
     * @param array $metastazs
     */
    abstract public function deleteMany($dimension, array $metastazs);

    /**
     * Constructor
     *
     * @param MetastazContainer $metastazContainer
     */
    public function __construct(MetastazContainer $metastaz_container)
    {
        $this->setMetastazContainer($metastaz_container);

        if($metastaz_container->isInstancePoolingEnabled())
        {
            $dimension = $metastaz_container->getMetastazDimension();
            if (!isset(self::$pool[$dimension]))
                self::$pool[$dimension] = array();
            if (!isset(self::$insertPool[$dimension]))
                self::$insertPool[$dimension] = array();
            if (!isset(self::$updatePool[$dimension]))
                self::$updatePool[$dimension] = array();
            if (!isset(self::$deletePool[$dimension]))
                self::$deletePool[$dimension] = array();
        }
    }

    /**
     * isLoaded
     *
     * @return boolean
     */
    public function isLoaded()
    {
        return $this->is_loaded;
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
     * Retrieve a Metastaz from the pool
     *
     * @param string $dimension
     * @param string $namespace
     * @param string $key
     * @param string $culture
     * @return mixed
     */
    public function getFromPool($dimension, $namespace, $key, $culture = null)
    {
        if(!$this->isLoaded())
        {
            throw new NotFoundHttpException(
                sprintf('Instance pooling error: Load metastaz data on the Object with the following dimension: %s.',
                    $dimension
                )
            );
        }

        try {
            return self::$pool[$dimension][$namespace][$key];
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Add or update a Metastaz in the pool
     *
     * @param string $dimension
     * @param string $namespace
     * @param string $key
     * @param string $value
     * @param string $culture
     */
    public function putInPool($dimension, $namespace, $key, $value, $culture = null)
    {
        if(!$this->isLoaded())
        {
            throw new NotFoundHttpException(
                sprintf('Instance pooling error: Load metastaz data on the Object with the following dimension: %s.',
                    $dimension
                )
            );
        }

        $previousValue = $this->getFromPool($dimension, $namespace, $key, $culture);
        if($value === $previousValue)
        {
            // Already exist and not changed
            return;
        }

        $metastaz = array($key => $value);
        if(null === $previousValue)
        {
            // Insert
            if(!isset(self::$pool[$dimension][$namespace]))
                self::$pool[$dimension][$namespace] = array();
            if(!isset(self::$insertPool[$dimension][$namespace]))
                self::$insertPool[$dimension][$namespace] = array();

            self::$pool[$dimension][$namespace] = array_merge(
                self::$pool[$dimension][$namespace],
                $metastaz
            );

            self::$insertPool[$dimension][$namespace] = array_merge(
                self::$insertPool[$dimension][$namespace],
                $metastaz
            );

            return;
        }

        // Update
        if(!isset(self::$updatePool[$dimension][$namespace]))
            self::$updatePool[$dimension][$namespace] = array();

        self::$pool[$dimension][$namespace] = array_merge(
            self::$pool[$dimension][$namespace],
            $metastaz
        );

        self::$updatePool[$dimension][$namespace] = array_merge(
            self::$updatePool[$dimension][$namespace],
            $metastaz
        );
    }

    /**
     * Remove a Metastaz from the pool
     *
     * @param string $dimension
     * @param string $namespace
     * @param string $key
     */
    public function deleteFromPool($dimension, $namespace, $key)
    {
        if(!$this->isLoaded())
        {
            throw new NotFoundHttpException(
                sprintf('Instance pooling error: Load metastaz data on the Object with the following dimension: %s.',
                    $dimension
                )
            );
        }

        if(null === $delete = $this->getFromPool($dimension, $namespace, $key, $culture))
        {
            // Remove a non existing metastaz
            return;
        }

        if(!isset(self::$deletePool[$dimension][$namespace]))
            self::$deletePool[$dimension][$namespace] = array();

        self::$deletePool[$dimension][$namespace][$key] = $delete;
        unset(self::$pool[$dimension][$namespace][$key]);
    }

    /**
     * Retrieve all Metastaz from the pool for a given object dimension
     *
     * @param string $dimension
     */
    public function getAllFromPool($dimension)
    {
        if(!$this->isLoaded())
        {
            throw new NotFoundHttpException(
                sprintf('Instance pooling error: Load metastaz data on the Object with the following dimension: %s.',
                    $dimension
                )
            );
        }

        return self::$pool[$dimension];
    }

    /**
     * Remove all Metastaz from the pool
     *
     * @param string $dimension
     */
    public function deleteAllFromPool($dimension)
    {
        if(!$this->isLoaded())
        {
            throw new NotFoundHttpException(
                sprintf('Instance pooling error: Load metastaz data on the Object with the following dimension: %s.',
                    $dimension
                )
            );
        }

        if(self::$pool[$dimension])
        {
            self::$deletePool[$dimension] = array_merge(
                self::$deletePool[$dimension],
                self::$pool[$dimension]
            );
            unset(self::$pool[$dimension]);
        }
    }

    /**
     * Load Metastaz related to an object
     *
     * @param string $dimension
     */
    public function load($dimension)
    {
        if (!$this->isLoaded())
        {
            self::$pool[$dimension] = $this->getAll($dimension);
            $this->is_loaded = true;
        }
    }

    /**
     * Flush Metastaz related to an object
     *
     * @param string $dimension
     */
    public function flush($dimension)
    {
        if(!$this->isLoaded())
        {
            throw new NotFoundHttpException(
                sprintf('Instance pooling error: Load metastaz data on the Object with the following dimension: %s.',
                    $dimension
                )
            );
        }

        if(self::$insertPool)
        {
            $this->putMany($dimension, self::$insertPool[$dimension]);
        }

        if(self::$deletePool)
        {
            $this->deleteMany($dimension, self::$deletePool[$dimension]);
        }
    }
}
