<?php

namespace Metastaz;

/**
 * MetastazPool
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: LGPL
 */
class MetastazPool
{
    /**
     * Pool
     * Insert pool
     * Update pool
     * Delete pool
     */
    protected $pool, $insert_pool, $update_pool, $delete_pool = array();

    /**
     * Is loaded
     */
    protected $is_loaded = false;

    protected $dimension = null;

    /**
     * Load
     */
    public function __construct($dimension, $metastazs = array())
    {
        $this->setDimension($dimension);

        $this->pool = array();
        $this->insert_pool = array();
        $this->update_pool = array();
        $this->delete_pool = array();

        if(!empty($metastazs))
            $this->load($metastazs);
    }

    public function setDimension($dimension)
    {
        $this->dimension = $dimension;
    }

    public function getDimension()
    {
        return $this->dimension;
    }

    public function load($metastazs)
    {
        $this->pool = $metastazs;
        $this->is_loaded = true;
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
     * Get
     */
    public function get($namespace, $key, $culture = null)
    {
        return isset($this->pool[$namespace]) && isset($this->pool[$namespace][$key]) ?
            $this->pool[$namespace][$key] :
            null
        ;
    }

    /**
     * Add
     */
    public function add($namespace, $key, $value, $culture = null)
    {
        $previousValue = $this->get($namespace, $key, $culture);
        if($value === $previousValue)
        {
            // Already exist and not changed: nothing to do
            return;
        }

        $metastaz = array($key => $value);

        // Put in the pool
        if(!isset($this->pool[$namespace]))
            $this->pool[$namespace] = array();

        $this->pool[$namespace] = array_merge(
            $this->pool[$namespace],
            $metastaz
        );

        if(null === $previousValue)
        {
            // To insert
            if(!isset($this->insert_pool[$namespace]))
                $this->insert_pool[$namespace] = array();

            $this->insert_pool[$namespace] = array_merge(
                $this->insert_pool[$namespace],
                $metastaz
            );
        }
        else
        {
            // To update
            if(!isset($this->update_pool[$namespace]))
                $this->update_pool[$namespace] = array();

            $this->update_pool[$namespace] = array_merge(
                $this->update_pool[$namespace],
                $metastaz
            );
        }
    }

    /**
     * Delete
     */
    public function delete($namespace, $key)
    {
        $previousValue = $this->get($namespace, $key);
        if(null === $previousValue)
        {
            // Remove a non existing metastaz: nothing to do
            return;
        }

        if(!isset($this->delete_pool[$namespace]))
            $this->delete_pool[$namespace] = array();

        $this->delete_pool[$namespace][$key] = $previousValue;

        unset(
            $this->pool[$namespace][$key],
            $this->insert_pool[$namespace][$key],
            $this->update_pool[$namespace][$key]
        );
    }

    /**
     * Delete all
     */
    public function deleteAll()
    {
        $this->delete_pool = array_merge(
            $this->delete_pool,
            $this->pool
        );

        $this->pool = $this->insert_pool = $this->update_pool = array();
    }

    /**
     * Get all
     */
    public function getAll()
    {
        return $this->pool;
    }

    /**
     * Get inserts
     */
    public function getInserts()
    {
        return $this->insert_pool;
    }

    /**
     * Get updates
     */
    public function getUpdates()
    {
        return $this->update_pool;
    }

    /**
     * Get deletes
     */
    public function getDeletes()
    {
        return $this->delete_pool;
    }
}
