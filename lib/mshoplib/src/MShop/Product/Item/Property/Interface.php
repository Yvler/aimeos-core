<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2014
 * @package MShop
 * @subpackage Product
 */


/**
 * Product property item interface
 *
 * @package MShop
 * @subpackage Product
 */
interface MShop_Product_Item_Property_Interface
	extends MShop_Common_Item_Interface, MShop_Common_Item_Typeid_Interface
{
	/**
	 * Returns the language id of the property item
	 *
	 * @return string Language ID of the property item
	 */
	public function getLanguageId();

	/**
	 * Sets the Language Id of the property item
	 *
	 * @param string $id New Language ID of the property item
	 * @return void
	 */
	public function setLanguageId( $id );
	
	/**
	 * Returns the parent id of the product property item
	 *
	 * @return integer|null Parent ID of the product property item
	 */
	public function getParentId();
	
	/**
	 * Sets the new parent ID of the product property item
	 *
	 * @param integer $id Parent ID of the product property item
	 */
	public function setParentId( $id );

	/**
	 * Returns the value of the property item.
	 *
	 * @return string Value of the property item
	 */
	public function getValue();

	/**
	 * Sets the new value of the property item.
	 *
	 * @param string $value Value of the property item
	 * @return void
	 */
	public function setValue( $value );

}
