<?php

namespace Metastaz;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Metastaz\Bundle\MetastazTemplateBundle\MetastazTemplateBundle;

/**
 * MetastazContainer manage Metastaz (MetastazStore, MetastazTemplate)
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Mirsal ENNAIME <mirsal@mirsal.fr>
 * @licence: GPL
 */
class MetastazContainer
{
    /**
     * Parameters
     */
    protected $parameters = array();

    /**
     * Shared
     */
    static protected $shared = array();

    /**
     * Constructor
     *
     * @param array $parameters
     */
    public function __construct(array $parameters = array())
    {
        $this->setParameters($parameters);
    }

    /**
     * Set parameters
     *
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Get parameters
     *
     * @return parameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Has parameter
     *
     * @param string $name
     * @return boolean
     */
    public function hasParameter($name)
    {
        return isset($this->parameters[$name]);
    }

    /**
     * Get parameter
     *
     * @param string $name
     * @throw Exception
     * @return mixed
     */
    public function getParameter($name)
    {
        if (!$this->hasParameter($name))
            throw new NotFoundHttpException(sprintf('Missing %s parameter', $name));
        return $this->parameters[$name];
    }

    /**
     * Get MetastazId Object
     *
     * @return string
     */
    public function getMetastazId()
    {
        $obj = $this->getParameter('metastaz.object');
        return get_class($obj).$obj->getMetastazDimension();
    }

    /**
     * Get MetastazTemplate
     *
     * @throw NotFoundHttpException
     * @return MetastazTemplate
     */
    public function getMetastazTemplate()
    {
        $obj = $this->getParameter('metastaz.object');
        $share_key = 'template.'.strtolower($obj->getMetastazTemplateName());
        if (isset(self::$shared[$share_key]))
        {
            return self::$shared[$share_key];
        }

        // Retrieve MetastazTemplate by its name
        $em = MetastazTemplateBundle::getContainer()->get('doctrine')->getEntityManager('metastaz_template');
        $re = $em->getRepository('MetastazTemplateBundle:MetastazTemplate');
        $template = $re->findOneByName($obj->getMetastazTemplateName());

        if ($template) {
            return self::$shared[$share_key] = $template;
        }
        else {
            throw new NotFoundHttpException(
                sprintf('Unable to find the following MetastazTemplate: %s.', $obj->getMetastazTemplateName())
            );
        }
    }

    /**
     * Get Store
     *
     * @throw NotFoundHttpException
     * @return MetastazStore
     */
    public function getMetastazStoreService()
    {
        $class = $this->getParameter('metastaz.store_class');
        $share_key = 'store.'.strtolower($class);
        if (isset(self::$shared[$share_key]))
        {
            return self::$shared[$share_key];
        }

        $_class = 'Metastaz\\Stores\\'.$class;
        if(class_exists($_class)) {
            $store = new $_class($this);
            return self::$shared[$share_key] = $store;
        }
        else {
            throw new NotFoundHttpException(
                sprintf('Unable to find the following MetastazStore: %s.', $_class)
            );
        }
    }

    /**
     * To get a Metastaz value for a specified Metastaz namespace and key
     *
     * @param string $namespace
     * @param string $key
     * @param string $culture
     * @return mixed
     */
    public function get($namespace, $key, $culture = null)
    {
        return $this->getMetastazStoreService()->get(
            $this->getMetastazId(),
            $namespace,
            $key,
            $culture
        );
    }

    /**
     * To put a Metastaz value for a specified Metastaz namespace and key
     *
     * @throw NotFoundHttpException
     * @param string $namespace
     * @param string $key
     * @param string $value
     * @param string $culture
     */
    public function put($namespace, $key, $value, $culture = null)
    {
        $template = $this->getMetastazTemplate();
        if(!$template->hasField($namespace, $key))
        {
            throw new NotFoundHttpException(
                sprintf('The MetastazTemplate "%s" doesn\'t contain the following field {namespace: "%s", key: "%s"}.',
                    $template->getName(),
                    $namespace,
                    $key
                )
            );
        }

        $this->getMetastazStoreService()->put(
            $this->getMetastazId(),
            $namespace,
            $key,
            $value,
            $culture
        );
    }

    /**
     * To get all Metastaz value order by key for a specified Metastaz namespace
     *
     * @param string $namespace
     * @param string $culture
     * @return array
     */
    public function getAll($namespace, $culture = null)
    {
        return $this->getMetastazStoreService()->getAll(
            $this->getMetastazId(),
            $namespace,
            $culture
        );
    }

    /**
     * Delete a Metastaz for a specified Metastaz namespace and key
     *
     * @param string $namespace
     * @param string $key
     */
    public function delete($namespace, $key)
    {
        $this->getMetastazStoreService()->delete(
            $this->getMetastazId(),
            $namespace,
            $key
        );
    }

    /**
     * Delete all Metastaz for a specified Metastaz dimension
     *
     */
    public function deleteAll()
    {
        $this->getMetastazStoreService()->deleteAll($this->getMetastazId());
    }
}
