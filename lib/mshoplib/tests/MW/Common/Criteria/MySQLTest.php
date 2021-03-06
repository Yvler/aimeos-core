<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */

/**
 * Test class for MySQL search criteria class.
 */
class MW_Common_Criteria_MySQLTest extends PHPUnit_Framework_TestCase
{
	private $_object;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$context = TestHelper::getContext( 'unit' );
		$dbm = $context->getDatabaseManager();
		$conn = $dbm->acquire();

		$this->_object = new MW_Common_Criteria_MySQL( $conn );

		$dbm->release( $conn );
	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		unset( $this->_object );
	}


	public function testCreateFunction()
	{
		$params = array( 'listtype', 'langid', 'test string' );

		$str = $this->_object->createFunction( 'catalog.index.text.relevance', $params );
		$this->assertEquals( 'catalog.index.text.relevance("listtype","langid"," +test* +string*")', $str );

		$str = $this->_object->createFunction( 'sort:catalog.index.text.relevance', $params );
		$this->assertEquals( 'sort:catalog.index.text.relevance("listtype","langid"," +test* +string*")', $str );
	}

}
