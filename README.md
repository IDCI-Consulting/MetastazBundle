Welcome to the MetastazBundle
=============================

What is MetastazBundle?
-----------------------

This bundle provide an interface which once implemented allow you to add 
metadata on your model objects. You can define 'Template' which you can use to 
specify metadata set. This 'Templates' are store with Doctrine2.


Installation requirements
-------------------------

* Symfony2 
* Doctrine2


Installation
------------

First get the source code and put it under a folder name "metastaz" in your Symfony2 vendor directory

    git clone git@github.com:idciconsulting/MetastazBundle.git vendor/metastaz

Add the 'Metastaz' namespace in registerNamespaces (app/autoload.php):

    'Metastaz'         => array(__DIR__.'/../vendor/metastaz/src', __DIR__.'/../vendor/metastaz/lib'),

Add 'MetastazBundle' and 'MetastazTemplateBundle' in registerBundles (app/AppKernel.php):

    new Metastaz\Bundle\MetastazBundle\MetastazBundle(),
    new Metastaz\Bundle\MetastazTemplateBundle\MetastazTemplateBundle(),

Configuration
-------------

To activate or not Template verification:

Configure connection (app/config/config.yml):

    # Doctrine Configuration

    doctrine:
        dbal:
            connections:
                default:
                    ...

                metastaz:
                    driver:   %metastaz_database_driver%
                    host:     %metastaz_database_host%
                    port:     %metastaz_database_port%
                    dbname:   %metastaz_database_name%
                    user:     %metastaz_database_user%
                    password: %metastaz_database_password%
                    charset:  UTF8

                metastaz_template:
                    driver:   %metastaz_template_database_driver%
                    host:     %metastaz_template_database_host%
                    port:     %metastaz_template_database_port%
                    dbname:   %metastaz_template_database_name%
                    user:     %metastaz_template_database_user%
                    password: %metastaz_template_database_password%
                    charset:  UTF8

        orm:
            auto_generate_proxy_classes: %kernel.debug%
            default_entity_manager:   default

            entity_managers:
                default:
                    connection:       default
                    mappings:
                        ...

                metastaz:
                    connection:       metastaz
                    mappings:
                        MetastazBundle: ~

                metastaz_template:
                    connection:       metastaz_template
                    mappings:
                        MetastazTemplateBundle: ~

Set connection parameters (app/config/parameters.ini):

    metastaz_database_driver   = pdo_mysql
    metastaz_database_host     = localhost
    metastaz_database_port     = 3306
    metastaz_database_name     = ...
    metastaz_database_user     = ...
    metastaz_database_password = ...

    metastaz_template_database_driver   = pdo_mysql
    metastaz_template_database_host     = localhost
    metastaz_template_database_port     = 3306
    metastaz_template_database_name     = ...
    metastaz_template_database_user     = ...
    metastaz_template_database_password = ...

Build table:

    php app/console doctrine:schema:create --em="metastaz"
    php app/console doctrine:schema:create --em="metastaz_template"

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
            // You have to set the Template Name that correspond to this object
            // return get_class($this);
            return 'TemplateName'; 
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
                        'metastaz.store_parameters' => array(
                            'connexion_name' => 'matestaz'
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
