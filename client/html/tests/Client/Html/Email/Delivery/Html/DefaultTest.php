<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */

class Client_Html_Email_Delivery_Html_DefaultTest extends PHPUnit_Framework_TestCase
{
	private static $_orderItem;
	private static $_orderBaseItem;
	private $_object;
	private $_context;
	private $_emailMock;


	public static function setUpBeforeClass()
	{
		$orderManager = MShop_Order_Manager_Factory::createManager( TestHelper::getContext() );
		$orderBaseManager = $orderManager->getSubManager( 'base' );

		$search = $orderManager->createSearch();
		$search->setConditions( $search->compare( '==', 'order.datepayment', '2008-02-15 12:34:56' ) );
		$result = $orderManager->searchItems( $search );

		if( ( self::$_orderItem = reset( $result ) ) === false ) {
			throw new Exception( 'No order found' );
		}

		self::$_orderBaseItem = $orderBaseManager->load( self::$_orderItem->getBaseId() );
	}


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$this->_context = TestHelper::getContext();
		$this->_emailMock = $this->getMock( 'MW_Mail_Message_None' );

		$paths = TestHelper::getHtmlTemplatePaths();
		$this->_object = new Client_Html_Email_Delivery_Html_Default( $this->_context, $paths );

		$view = TestHelper::getView( 'unittest', $this->_context->getConfig() );
		$view->extOrderItem = self::$_orderItem;
		$view->extOrderBaseItem = self::$_orderBaseItem;
		$view->addHelper( 'mail', new MW_View_Helper_Mail_Default( $view, $this->_emailMock ) );

		$this->_object->setView( $view );
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


	public function testGetHeader()
	{
		$output = $this->_object->getHeader();
		$this->assertNotNull( $output );
	}


	public function testGetBody()
	{
		$ds = DIRECTORY_SEPARATOR;
		$file = '..' . $ds . 'themes' . $ds . 'classic' . $ds . 'css' . $ds . 'images' . $ds . 'arcavias.png';
		$this->_context->getConfig()->set( 'client/html/email/logo', $file );

		$this->_emailMock->expects( $this->once() )->method( 'embedAttachment' )
			->will( $this->returnValue( 'cid:123-unique-id' ) );

		$this->_emailMock->expects( $this->once() )->method( 'setBodyHtml' )
			->with( $this->matchesRegularExpression( '#<html>.*<title>E-mail notification</title>.*<meta.*Arcavias.*<body>#smu' ) );

		$output = $this->_object->getBody();

		$this->assertStringStartsWith( '<html>', $output );
		$this->assertContains( 'cid:123-unique-id', $output );
	}


	public function testGetSubClientInvalid()
	{
		$this->setExpectedException( 'Client_Html_Exception' );
		$this->_object->getSubClient( 'invalid', 'invalid' );
	}


	public function testGetSubClientInvalidName()
	{
		$this->setExpectedException( 'Client_Html_Exception' );
		$this->_object->getSubClient( '$$$', '$$$' );
	}


	public function testProcess()
	{
		$this->_object->process();
	}
}
