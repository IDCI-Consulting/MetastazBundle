Welcome to the MetastazBundle
=============================

What is MetastazBundle?
-----------------------

This bundle provides an interface with which you can manipulate
metadata tied to arbitrary model objects. You can also define
'Templates' (optional dynamic schemas) in order to enable
automatic constraints and form generation.
Templates are stored in a database using Doctrine2.


Installation requirements
-------------------------

* Symfony2
* Doctrine2
* DoctrineFixturesBundle
  (http://symfony.com/doc/current/cookbook/doctrine/doctrine_fixtures.html)

Optional dependencies
--------------------

* MongoDB (http://symfony.com/doc/current/cookbook/doctrine/mongodb.html)


Installation
------------

If your project uses the *git* versioning system,
add a submodule for metastaz:

    git submodule add git://github.com/idciconsulting/MetastazBundle.git vendor/metastaz


Alternatively, clone the repository and put it under a folder named "metastaz"
in your Symfony2 vendor directory

    git clone git://github.com/idciconsulting/MetastazBundle.git vendor/metastaz

If you do **not** have git installed nor are you willing to install it,
You can install Metastaz from a snapshot (we would **not** recommend it though):

    wget https://github.com/idciconsulting/MetastazBundle/tarball/master -O- |tar xz --transform 's|^[^/]\+/|vendor/metastaz/|'

Add the 'Metastaz' namespace in registerNamespaces (app/autoload.php):

    'Metastaz'         => array(__DIR__.'/../vendor/metastaz/src', __DIR__.'/../vendor/metastaz/lib'),

Add 'MetastazBundle' and 'MetastazTemplateBundle' in registerBundles
(app/AppKernel.php):

    new Metastaz\Bundle\MetastazBundle\MetastazBundle(),
    new Metastaz\Bundle\MetastazTemplateBundle\MetastazTemplateBundle(),

Configuration
-------------

Add this in your configuration (app/config/config.yml):

    # Metastaz Configuration
    metastaz:
        container:
            use_template: false
            instance_pooling: false
        store:
            class: DoctrineORMMetastazStore
            parameters:
                connection: metastaz

In order to activate update constraints based on Templates,
set the *use_tempate* parameter to true:

    # Metastaz Configuration
    metastaz:
        container:
            use_template: true

Metastaz container can hold records in memory until you
manually flush them to the database. In order to enable
this feature, set the *instance_pool* parameter to true:

    # Metastaz Configuration
    metastaz:
        container:
            instance_pooling: true

You can select different stores or create yours.
MetastazBundle provide an ORM and an ODM store.

In order to use them, set the store *class* parameter to
*DoctrineORMMetastazStore* or *DoctrineODMMetastazStore*

    # Metastaz Configuration
    metastaz:
        store:
            class: DoctrineORMMetastazStore

Configure database connections (app/config/config.yml):

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

If you are using MongoDB along with the doctrine bundles,
define a *doctrine_mongodb* connection in metastaz:

    doctrine_mongodb:
        connections:
            metastaz:
                server: mongodb://localhost:27017
                options:
                    connect: true

        document_managers:
            metastaz:
                connection:           metastaz
                database:             metastaz_db
                mappings:
                    MetastazBundle: ~

Build table:

    php app/console doctrine:schema:create --em="metastaz"
    php app/console doctrine:schema:create --em="metastaz_template"

In order to use the template manager, add routes to your routing.yml
like this one:

    _metastaz_template:
        resource: "@MetastazTemplateBundle/Controller/MetastazTemplateController.php"
        type:     annotation
        prefix:   /metastaz/template

In order to check if this controller is well loaded,
you can execute the following command:

    php app/console router:debug

You should see these routes:

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

Loading Fixtures
-------------

If you have well added DoctrineFixturesBundle,
load default MetastazTemplateFieldType:

    php app/console doctrine:fixtures:load --em="metastaz_template"

You can now add templates and define their fields by pointing your web browser
to http://your_vhost/[app_dev.php]/metastaz/template/ and configure your
templates as you like.

Building Template forms
----------------------

You can generate template forms by running
the following command with the console:

    php app/console metastaz:generate:form

Take a look in app/Application/Form

Using Metastaz
--------------

In the class within which you would like to use Metastaz:

    use Metastaz\Interfaces\MetastazInterface;
    use Metastaz\MetastazContainer;

    class MyClass implements MetastazInterface
    {
        ...

        /**
         * Holds MetastazContainer Objects
         */
        protected static $metastaz_containers = null;

        ...

        /**
         * @see Metastaz\Interfaces\MetastazInterface
         *
         * Generates an identifier referring to this
         * model object within Metastaz
         */
        public function getMetastazDimensionId()
        {
            return $this->getId();
        }

        /**
         * Generates an identifier referring to this
         * model class within Metastaz
         *
         * @return string 
         */
        public function getMetastazTemplateName()
        {
            /* You must define a Template Name for this object */
            // return get_class($this);
            return 'TemplateName'; 
        }

        /**
         * @see Metastaz\Interfaces\MetastazInterface
         */
        public function getMetastazContainer()
        {
            if(!isset(self::$metastaz_containers[$this->getMetastazDimensionId()]))
            {
                self::$metastaz_containers[$this->getMetastazDimensionId()] = new MetastazContainer(
                    array('object' => $this)
                );
            }
            return self::$metastaz_containers[$this->getMetastazDimensionId()];
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

You can override the Metastaz configuration for each
Metastazed Object by passing additional parameters
while instanciating the MetastazContainer

        /**
         * @see Metastaz\Interfaces\MetastazInterface
         */
        public function getMetastazContainer()
        {
            if(!isset(self::$metastaz_containers[$this->getMetastazDimensionId()]))
            {
                self::$metastaz_containers[$this->getMetastazDimensionId()] = new MetastazContainer(
                    array(
                        'object' => $this,
                        'container' => array(
                            'use_template' => true,
                            'instance_pooling' => true
                        ),
                        'store' => array(
                            'class' => 'DoctrineODMMetastazStore',
                            'parameters' => array('connection' => 'metastaz')
                        )
                    )
                );
            }
            return self::$metastaz_containers[$this->getMetastazDimensionId()];
        }

Now you can use metastazed classes this way:

    $myObj = new MyClass();

    // Store a metadata entry
    $myObj->putMetastaz('ns', 'key', 'value');

    // Retrieve a metadata by its namespace and key
    $YourClassObj->getMetastaz('ns', 'key');

If the instance pool is enabled,
you must call the flushMetastaz method
in order to process write operations.

    $YourClassObj->flushMetastaz();

Note:
Metastaz will soon be able to automatically flush
its data on a Doctrine Entity Manager flush call

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
