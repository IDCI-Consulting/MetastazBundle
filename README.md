Welcome to the MetastazBundle
=============================

What is MetastazBundle?
-----------------------

This bundle provide an interface which once implemented allow you to add 
metadata on your model objects. You can define 'Template' which you can use to 
specify metadata set. This 'Templates' are store with Doctrine2.


Installation requirements
-------------------------
### Symfony2 and Doctrine2 requirements


Installation
------------

git clone this project to your symfony2 vendor directory.

add this to the registerNamespaces in autoload.php:

    'Metastaz'         => array(__DIR__.'/../vendor/metastaz/src', __DIR__.'/../vendor/metastaz/lib'),

add this to the registerBundles in AppKernel.php:

    new Metastaz\MetastazTemplateBundle\MetastazBundle(),
    new Metastaz\MetastazTemplateBundle\MetastazTemplateBundle(),


Configuration
-------------

To activate or not Template verification:



To use the template manager, add routes to your routing.yml like this:

_metastaz_template:
    resource: "@MetastazTemplateBundle/Controller/MetastazTemplateController.php"
    type:     annotation
    prefix:   /metastaz/template


How use Metastaz
----------------

In the class which you would like to use Metastaz:

use Metastaz\Interfaces\MetastazInterface;
use Metastaz\MetastazContainer;

class YourClass implements MetastazInterface
{
...
    /**
     * MetastazContainer Objects
     * This is a class variable
     */
    protected static $metastaz_containers = null;


    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function getMetastazDimension()
    {
        return $this->getId();
    }

    /**
     * Get metastaz_template_name
     *
     * @return string 
     */
    public function getMetastazTemplateName()
    {
        return $this->getType()->getTemplateName();
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function getMetastazContainer()
    {
        if(!isset(self::$metastaz_containers[$this->getMetastazDimension()]))
        {
            self::$metastaz_containers[$this->getMetastazDimension()] = new MetastazContainer(
                array(
                    'metastaz.object' => $this,
                    'metastaz.store_class' => 'DoctrineMetastazStore',
                    'metastaz_store_parameters' => array(
                        'database_driver' => 'pdo_mysql',
                        'database_host' => 'localhost',
                        'database_port' => '3306',
                        'database_name' => 'allopneus',
                        'database_user' => 'allopneus',
                        'database_password' => 'allopneus'
                    )
                )
            );
        }
        return self::$metastaz_containers[$this->getMetastazDimension()];
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function getMetastaz($namespace, $key, $culture = null)
    {
        return $this->getMetastazContainer()->get($namespace, $key, $culture);
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function putMetastaz($namespace, $key, $value, $culture = null)
    {
        return $this->getMetastazContainer()->put($namespace, $key, $value, $culture);
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function getAllMetastaz($namespace)
    {
        return $this->getMetastazContainer()->getAll($namespace);
    }

    /**
     * @see Metastaz\Interfaces\MetastazInterface
     */
    public function deleteMetastaz($namespace, $key)
    {
        return $this->getMetastazContainer()->delete($namespace, $key);
    }
}

Now you can use metastazed class like this:


$YourClassObj = new $YourClass();

// To put a metadata on your class object 
$YourClassObj->putMetastaz('ns', 'key', 'value');

// To retrieve a metadata by its namespace and key
$YourClassObj->getMetastaz('ns', 'key');


Licence
-------

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

A copy of the software license is included in the LICENSE file

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
