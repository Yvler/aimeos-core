<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @package MShop
 * @subpackage Common
 */


/**
 * Common interface for items that carry sorting informations.
 *
 * @package MShop
 * @subpackage Common
 */
interface MShop_Common_Item_Position_Interface
{
	/**
	 * Returns the position of the item in the list.
	 *
	 * @return integer Position of the item in the list
	 */
	public function getPosition();

	/**
	 * Sets the new position of the item in the list.
	 *
	 * @param integer $pos position of the item in the list
	 * @return void
	 */
	public function setPosition( $pos );
}
