Welcome to the MetastazBundle
=============================

What is MetastazBundle?
-----------------------

This bundle provides an interface which once implemented allows you to add 
metadata on your model objects. You can define 'Templates' that you can use to 
specify metadata set. These 'Templates' are stored with Doctrine2.


Installation requirements
-------------------------

* Symfony2
* Doctrine2
* DoctrineFixturesBundle (http://symfony.com/doc/current/cookbook/doctrine/doctrine_fixtures.html)

Installation options
--------------------

* MongoDB (http://symfony.com/doc/current/cookbook/doctrine/mongodb.html)


Installation
------------

First, get the source code and put it under a folder name "metastaz" in your Symfony2 vendor directory

    git clone git@github.com:idciconsulting/MetastazBundle.git vendor/metastaz

Add the 'Metastaz' namespace in registerNamespaces (app/autoload.php):

    'Metastaz'         => array(__DIR__.'/../vendor/metastaz/src', __DIR__.'/../vendor/metastaz/lib'),

Add 'MetastazBundle' and 'MetastazTemplateBundle' in registerBundles (app/AppKernel.php):

    new Metastaz\Bundle\MetastazBundle\MetastazBundle(),
    new Metastaz\Bundle\MetastazTemplateBundle\MetastazTemplateBundle(),

Configuration
-------------

Add this in your configuration (app/config/config.yml):

    # Metastaz Configuration
    metastaz:
        container:
            use_template: false
            instance_pool: false
        store:
            class: DoctrineORMMetastazStore
            parameters:
                connection: metastaz

To activate or not Template verification just set to true the use_tempate parameter:

    # Metastaz Configuration
    metastaz:
        container:
            use_template: true

Metastaz container can act like an active records.
To enable this feature active the instance_pool:

    # Metastaz Configuration
    metastaz:
        container:
            instance_pool: true

You can define different store or create yours. Metastaz bundle provide an ORM and an ODM.
To use them set the class store parameters (DoctrineORMMetastazStore or DoctrineODMMetastazStore)

    # Metastaz Configuration
    metastaz:
        store:
            class: DoctrineORMMetastazStore

Configure connections (app/config/config.yml):

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

To check if this controller is well loaded, you can execute the following command:

    php app/console router:debug

You must see this routes:

    metastaz_template               ANY    /metastaz/template/
    metastaz_template_show          ANY    /metastaz/template/{id}/show
    metastaz_template_new           ANY    /metastaz/template/new
    metastaz_template_field_new     ANY    /metastaz/template/{id}/new_field
    metastaz_template_field_edit    ANY    /metastaz/template/edit_field/{id}
    metastaz_template_field_update  POST   /metastaz/template/update_field/{id}
    metastaz_template_create        POST   /metastaz/template/create
    metastaz_template_field_create  POST   /metastaz/template/{id}/create_field
    metastaz_template_edit          ANY    /metastaz/template/{id}/edit
    metastaz_template_update        POST   /metastaz/template/{id}/update
    metastaz_template_delete        POST   /metastaz/template/{id}/delete
    metastaz_template_field_delete  POST   /metastaz/template/delete_field/{id}

Load Fixtures
-------------

If you have well added DoctrineFixturesBundle, load default MetastazTemplateFieldType:

    php app/console doctrine:fixtures:load --em="metastaz_template"


How to use Metastaz
-------------------

In the class within you would like to use Metastaz:

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

        ...

        /**
         * @see Metastaz\Interfaces\MetastazInterface
         */
        public function getMetastazDimensionId()
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
                    array('object' => $this)
                );
            }
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

        /**
         * @see Metastaz\Interfaces\MetastazInterface
         */
        public function deleteAllMetastaz()
        {
            return $this->getMetastazContainer()->deleteAll();
        }
    }

You can override the Metastaz configuration for each Metastazed Objects by passing more parameters
when instanciate the MetastazContainer

        /**
         * @see Metastaz\Interfaces\MetastazInterface
         */
        public function getMetastazContainer()
        {
            if(!isset(self::$metastaz_containers[$this->getMetastazDimension()]))
            {
                self::$metastaz_containers[$this->getMetastazDimension()] = new MetastazContainer(
                    array(
                        'object' => $this,
                        'container' => array(
                            'use_template' => true,
                            'instance_pool' => true
                        ),
                        'store' => array(
                            'class' => 'DoctrineODMMetastazStore',
                            'parameters' => array('connection' => 'metastaz')
                        )
                    )
                );
            }
        }

Now you can use metastazed class like this:

    $YourClassObj = new $YourClass();

    // To put a metadata on your class object 
    $YourClassObj->putMetastaz('ns', 'key', 'value');

    // To retrieve a metadata by its namespace and key
    $YourClassObj->getMetastaz('ns', 'key');

If you have activated the instance pool, you must call the flushMetastaz methode

    $YourClassObj->flushMetastaz();

Note:
A lister is comming soon to automaticaly flush metastaz data on an Doctrine Entity Manager flush call

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
