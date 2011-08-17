<?php

namespace Metastaz\Tests;

use Metastaz\MetastazPool;

/**
 * MetastazPoolTest
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @licence: GPL
 */
class MetastazPoolTest extends \PHPUnit_Framework_TestCase
{
    static $fixtures = array(
        'ns1' => array(
            'key1A' => 'valeur1A',
            'key1B' => 'valeur1B',
        ),
        'ns2' => array(
            'key2A' => 'valeur2A',
            'key2B' => 'valeur2B',
        )
    );

    public function setUp()
    {
        if (!class_exists('Metastaz\\MetastazPool')) {
            $this->markTestSkipped('MetastazPool is not available.');
        }
    }

    // Test constructor
    public function testConstructor()
    {
        $pool = new MetastazPool('dimension_1');
        $this->assertEquals(array(), $pool->getAll());
        $this->assertEquals(array(), $pool->getInserts());
        $this->assertEquals(array(), $pool->getUpdates());
        $this->assertEquals(array(), $pool->getDeletes());
        $this->assertFalse($pool->isLoaded());

        $pool = new MetastazPool('dimension_1', self::$fixtures);
        $this->assertEquals(self::$fixtures, $pool->getAll());
        $this->assertEquals(array(), $pool->getInserts());
        $this->assertEquals(array(), $pool->getUpdates());
        $this->assertEquals(array(), $pool->getDeletes());
        $this->assertTrue($pool->isLoaded());
    }

    // Test load
    public function testIsLoad()
    {
        $pool = new MetastazPool('dimension_1');
        $this->assertFalse($pool->isLoaded());
        $pool->load(self::$fixtures);
        $this->assertTrue($pool->isLoaded());
    }

    // Test get
    public function testGet()
    {
        $pool = new MetastazPool('dimension_1', self::$fixtures);
        $this->assertEquals('valeur1A', $pool->get('ns1', 'key1A'));
        $this->assertEquals('valeur1B', $pool->get('ns1', 'key1B'));
        $this->assertEquals('valeur2A', $pool->get('ns2', 'key2A'));
        $this->assertEquals('valeur2B', $pool->get('ns2', 'key2B'));
    }

    // Test add
    public function testAdd()
    {
        // If pool is empty
        $pool = new MetastazPool('dimension_1');
        $value = 'new_value_with_empty_pool';
        $pool->add('ns1', 'key1C', $value);
        $this->assertEquals($value, $pool->get('ns1', 'key1C'));

        // If pool has data
        $pool = new MetastazPool('dimension_2', self::$fixtures);
        $value = 'new_value_with_pool_data';
        $pool->add('ns1', 'key1C', $value);
        $this->assertEquals($value, $pool->get('ns1', 'key1C'));

        // If pool has updated data
        $value = 'update_value_with_pool_data';
        $pool->add('ns1', 'key1C', $value);
        $this->assertEquals($value, $pool->get('ns1', 'key1C'));
        $this->assertEquals(array('ns1' => array('key1C' => $value)), $pool->getUpdates());
    }

    // Test delete
    public function testDelete()
    {
        $pool = new MetastazPool('dimension_1', self::$fixtures);
        // Delete a non existing data
        $pool->delete('ns', 'key');
        $this->assertEquals(self::$fixtures, $pool->getAll());
        $this->assertEquals(array(), $pool->getDeletes());
        // Delete an existing data
        $pool->delete('ns1', 'key1A');
        $this->assertNull($pool->get('ns1', 'key1A'));
        $this->assertEquals(array('ns1' => array('key1A' => 'valeur1A')), $pool->getDeletes());
    }

    // Test getAll
    public function testGetAll()
    {
        $pool = new MetastazPool('dimension_1', self::$fixtures);
        $this->assertEquals(self::$fixtures, $pool->getAll());
    }

    // Test deleteAll
    public function testDeleteAll()
    {
        $pool = new MetastazPool('dimension_1', self::$fixtures);
        $pool->deleteAll();
        $this->assertEquals(array(), $pool->getAll());
        $this->assertEquals(array(), $pool->getInserts());
        $this->assertEquals(array(), $pool->getUpdates());
        $this->assertEquals(self::$fixtures, $pool->getDeletes());
    }
}
