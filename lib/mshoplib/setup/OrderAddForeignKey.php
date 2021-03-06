<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */


/**
 * Adds foreign key constraints for langid and curid columns in order base table.
 *
 * 2012-08-08
 * At this time the constrains are not needed anymore because of future dependency.
 * see: MW_Setup_Task_OrderDropForeignKeyOfLocale
 * -> Order domain table can be used on a differend database/ server
 */
class MW_Setup_Task_OrderAddForeignKey extends MW_Setup_Task_Abstract
{
	/**
	 * Returns the list of task names which this task depends on.
	 *
	 * @return array List of task names
	 */
	public function getPreDependencies()
	{
		return array();
	}


	/**
	 * Returns the list of task names which depends on this task.
	 *
	 * @return string[] List of task names
	 */
	public function getPostDependencies()
	{
		return array( 'TablesCreateMShop' );
	}


	/**
	 * Executes the task for MySQL databases.
	 */
	protected function _mysql()
	{
		// see: MW_Setup_Task_OrderDropForeignKeyOfLocale
	}
}
