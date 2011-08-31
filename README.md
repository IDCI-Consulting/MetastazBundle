Welcome to the MetastazBundle
=============================

What is MetastazBundle ?
========================

This bundle provides an interface with which you can manipulate metadata tied to
arbitrary model objects. You can also define 'Templates' (optional dynamic schemas) 
in order to enable automatic constraints and form generation.

Templates are stored in a database using Doctrine2.

This bundle provide a light web user interface to create metastaz template.
You can enable MetastazProductBundle to show how you can use metastaz with a
concret case (A catalog manager in this case).

Installation requirements
=========================

* Symfony2
* Doctrine2
* DoctrineFixturesBundle
  (http://symfony.com/doc/current/cookbook/doctrine/doctrine_fixtures.html)

Optional dependencies
=====================

* MongoDB (http://symfony.com/doc/current/cookbook/doctrine/mongodb.html)


Installation
============

If you have install symfony without vendors, simply add a dependencies in the *deps* file:

    [metastaz]
        git=http://github.com/idciconsulting/MetastazBundle.git

Then just run the vendors installation command:

    php bin/vendors install

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
=============

Add this in your configuration (app/config/config.yml):

    imports:
        - { resource: "@MetastazBundle/Resources/config/config.yml" }

    ...

    # Doctrine Configuration
    doctrine:
        dbal:
            connections:
                default:
                    driver:   %database_driver%
                    host:     %database_host%
                    port:     %database_port%
                    dbname:   %database_name%
                    user:     %database_user%
                    password: %database_password%
                    charset:  UTF8

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
                    mappings: ~

                metastaz:
                    connection:       metastaz
                    mappings:
                        MetastazBundle: ~

                metastaz_template:
                    connection:       metastaz_template
                    mappings:
                        MetastazTemplateBundle: ~

    ...

    # Metastaz Configuration
    metastaz:
        container:
            use_template: %metastaz_container_use_template%
            instance_pooling: %metastaz_container_instance_pooling%
        store:
            class: %metastaz_store_class%
            parameters:
                connection: metastaz

Set parameters (app/config/parameters.ini):

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

    metastaz_container_use_template     = false
    metastaz_container_instance_pooling = false
    metastaz_store_class                = DoctrineORMMetastazStore

In order to activate update constraints based on Templates,
set the *metastaz_container_use_template* parameter to true:

    metastaz_container_use_template     = true

Metastaz containers can hold records in memory until you
manually flush them to the database. In order to enable
this feature, set the *metastaz_container_instance_pooling* parameter to true:

    metastaz_container_instance_pooling = true

You can select different stores or create yours.
MetastazBundle provide an ORM and an ODM store.

In order to use them, set the store *metastaz_store_class* parameter to
*DoctrineORMMetastazStore* or *DoctrineODMMetastazStore*

    metastaz_store_class                = DoctrineORMMetastazStore

If you are using MongoDB along with the doctrine bundles,
you can define a *doctrine_mongodb* connection to store metastaz (app/config/config.yml):

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
    metastaz_template_form_show     ANY    /metastaz/template/{id}/form/show

Loading Fixtures
================

If you have well added DoctrineFixturesBundle,
load default MetastazTemplateFieldType:

    php app/console doctrine:fixtures:load --em="metastaz_template"

You can now add templates and define their fields by pointing your web browser
to http://your_vhost/[app_dev.php]/metastaz/template/ and configure your
templates as you like.

Building Template forms
=======================

You can generate template forms by running
the following command with the console:

    php app/console metastaz:generate:form

Take a look in app/Application/Form
To show this form in action, register the namespace *Application* (autoload.php)

    'Application'                       => __DIR__.'/',


Using web interfaces
====================


Using Metastaz
==============

You can choose two diffrents way to use metastaz.

 * extends MetastazObject
 * implements MetastazInterface

Which method use ?

If the metastazed object class needn't to extends an other class, you can simply
choose the first method: extends the abstract class MetastazObject. If not you must
have to implements MetastazInterface and define the right prototyped functions.

Extends MetastazObject
----------------------

In the class within which you would like to use Metastaz:

    use Metastaz\MetastazObject;

    class MyClass extends MetastazObject
    {
        ...
    }

Implements MetastazInterface
----------------------------

In the class within which you would like to use Metastaz:

    use Metastaz\Interfaces\MetastazInterface;
    use Metastaz\MetastazContainer;

    class MyClass implements MetastazInterface
    {
        ...

        /**
         * Holds MetastazContainer Objects
         */
        protected $metastaz_container = null;

        ...

        /**
         * @see Metastaz\Interfaces\MetastazInterface
         *
         * Generates an identifier referring to this
         * model object within Metastaz
         *
         * @return string
         */
        public function getMetastazDimensionId()
        {
            return $this->getId();
        }

        /**
         * @see Metastaz\Interfaces\MetastazInterface
         *
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
            if(null === $this->metastaz_container)
            {
                $this->metastaz_container = new MetastazContainer(
                    array('object' => $this)
                ;
            }
            return $this->metastaz_container;
        }

        /**
         * @see Metastaz\Interfaces\MetastazInterface
         */
        public function getMetastazIndexes()
        {
            return $this->getMetastazContainer()->getIndexedFields();
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
        public function deleteMetastaz($namespace, $key)
        {
            return $this->getMetastazContainer()->delete($namespace, $key);
        }

        /**
         * @see Metastaz\Interfaces\MetastazInterface
         */
        public function getAllMetastaz()
        {
            return $this->getMetastazContainer()->getAll();
        }

        /**
         * @see Metastaz\Interfaces\MetastazInterface
         */
        public function deleteAllMetastaz()
        {
            return $this->getMetastazContainer()->deleteAll();
        }

        /**
         * @see Metastaz\Interfaces\MetastazInterface
         */
        public function loadMetastaz()
        {
            return $this->getMetastazContainer()->load();
        }

        /**
         * @see Metastaz\Interfaces\MetastazInterface
         */
        public function persistMetastaz()
        {
            return $this->getMetastazContainer()->persist();
        }

        /**
         * @see Metastaz\Interfaces\MetastazInterface
         */
        public function flushMetastaz()
        {
            return $this->getMetastazContainer()->flush();
        }
    }


How custom metastaz configuration for each Metastazed class object
------------------------------------------------------------------

In each case, you can override the Metastaz configuration for
each Metastazed Object by passing additional parameters while 
instanciating the MetastazContainer

        /**
         * @see Metastaz\Interfaces\MetastazInterface
         */
        public function getMetastazContainer()
        {
            if(null === $this->metastaz_container)
            {
                $this->metastaz_container = new MetastazContainer(
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
                    );
            }
            return $this->metastaz_container;
        }


Retrieve data throw metastaz
----------------------------

Now you can use metastazed classes this way:

    $myObj = new MyClass();

    // Store a metadata entry
    $myObj->putMetastaz('ns', 'key', 'value');

    // Retrieve a metadata by its namespace and key
    $YourClassObj->getMetastaz('ns', 'key');

If the instance pool is enabled, you must call the *flushMetastaz* method
in order to process write operations. Else write operation will be done when you
call to putMetastaz method

    $YourClassObj->flushMetastaz();

Note:
Metastaz automatically persist data on a Doctrine Entity Manager persist call

Licence
=======

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

A copy of the software license is included in the LICENSE file

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
