<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */


/**
 * Adds locale records to tables.
 */
class MW_Setup_Task_MShopAddLocaleData extends MW_Setup_Task_Abstract
{
	/**
	 * Returns the list of task names which this task depends on.
	 *
	 * @return string[] List of task names
	 */
	public function getPreDependencies()
	{
		return array( 'MShopAddLocaleLangCurData' );
	}


	/**
	 * Returns the list of task names which depends on this task.
	 *
	 * @return array List of task names
	 */
	public function getPostDependencies()
	{
		return array( 'MShopSetLocale' );
	}


	/**
	 * Executes the task for MySQL databases.
	 */
	protected function _mysql()
	{
		$this->_process();
	}


	/**
	 * Creates new locale data if necessary
	 */
	protected function _process()
	{
		$iface = 'MShop_Context_Item_Interface';
		if( !( $this->_additional instanceof $iface ) ) {
			throw new MW_Setup_Exception( sprintf( 'Additionally provided object is not of type "%1$s"', $iface ) );
		}

		$this->_msg( 'Adding locale data if not yet present', 0 );


		// Set editor for further tasks
		$this->_additional->setEditor( 'core:setup' );


		$code = $this->_additional->getConfig()->get( 'setup/site', 'default' );

		$localeManager = MShop_Locale_Manager_Factory::createManager( $this->_additional, 'Default' );
		$siteManager = $localeManager->getSubManager( 'site' );

		try
		{
			$siteItem = $siteManager->createItem();
			$siteItem->setLabel( $code );
			$siteItem->setCode( $code );

			$siteManager->insertItem( $siteItem );
		}
		catch( MW_DB_Exception $e ) // already in the database
		{
			$this->_status( 'OK' );
			return;
		}

		$localeItem = $localeManager->createItem();
		$localeItem->setSiteId( $siteItem->getId() );
		$localeItem->setLanguageId( 'en' );
		$localeItem->setCurrencyId( 'EUR' );

		$localeManager->saveItem( $localeItem, false );

		$this->_status( 'done' );
	}


	/**
	 * Adds locale site data.
	 *
	 * @param MShop_Common_Manager_Interface $localeManager Locale manager
	 * @param array $data Associative list of locale site data
	 * @param string $manager Manager implementation name
	 * @param integer|null $parentId Parent id of the locale item
	 * @return array Associative list of keys from the data and generated site ID
	 */
	protected function _addLocaleSiteData( MShop_Common_Manager_Interface $localeManager, array $data, $manager = 'Default', $parentId = null )
	{
		$this->_msg( 'Adding data for MShop locale sites', 1 );

		$localeSiteManager = $localeManager->getSubManager( 'site', $manager );
		$siteItem = $localeSiteManager->createItem();
		$siteIds = array();

		foreach( $data as $key => $dataset )
		{
			try
			{
				$siteItem->setId( null );
				$siteItem->setCode( $dataset['code'] );
				$siteItem->setLabel( $dataset['label'] );
				$siteItem->setConfig( $dataset['config'] );
				$siteItem->setStatus( $dataset['status'] );

				$localeSiteManager->insertItem( $siteItem, $parentId );
				$siteIds[$key] = $siteItem->getId();
			}
			catch( Exception $e )
			{
				$search = $localeSiteManager->createSearch();
				$search->setConditions( $search->compare( '==', 'locale.site.code', $dataset['code'] ) );
				$result = $localeSiteManager->searchItems( $search );

				if( ( $item = reset( $result ) ) === false ) {
					throw new Exception( sprintf( 'No site for code "%1$s" available', $dataset['code'] ) );
				}

				$siteIds[$key] = $item->getId();
			}
		}

		$this->_status( 'done' );

		return $siteIds;
	}


	/**
	 * Adds locale currency data.
	 *
	 * @param MShop_Common_Manager_Interface $localeManager Locale manager
	 * @param array $data Associative list of locale currency data
	 */
	protected function _addLocaleCurrencyData( MShop_Common_Manager_Interface $localeManager, array $data )
	{
		$this->_msg( 'Adding data for MShop locale currencies', 1 );

		$currencyItemManager = $localeManager->getSubManager( 'currency', 'Default' );

		$num = $total = 0;

		foreach( $data as $key => $dataset )
		{
			$total++;

			$currencyItem = $currencyItemManager->createItem();
			$currencyItem->setCode( $dataset['id'] );
			$currencyItem->setLabel( $dataset['label'] );
			$currencyItem->setStatus( $dataset['status'] );

			try {
				$currencyItemManager->saveItem( $currencyItem );
				$num++;
			} catch( Exception $e ) {; } // if currency was already available
		}

		$this->_status( $num > 0 ? $num . '/' . $total : 'OK' );
	}


	/**
	 * Adds locale language data.
	 *
	 * @param MShop_Common_Manager_Interface $localeManager Locale manager
	 * @param array $data Associative list of locale language data
	 */
	protected function _addLocaleLanguageData( MShop_Common_Manager_Interface $localeManager, array $data )
	{
		$this->_msg( 'Adding data for MShop locale languages', 1 );

		$languageItemManager = $localeManager->getSubManager( 'language', 'Default' );

		$num = $total = 0;

		foreach( $data as $dataset )
		{
			$total++;
			$languageItem = $languageItemManager->createItem();
			$languageItem->setCode( $dataset['id'] );
			$languageItem->setLabel( $dataset['label'] );
			$languageItem->setStatus( $dataset['status'] );

			try {
				$languageItemManager->saveItem( $languageItem );
				$num++;
			} catch( Exception $e ) {; } // if language was already available
		}

		$this->_status( $num > 0 ? $num . '/' . $total : 'OK' );
	}


	/**
	 * Adds locale data.
	 *
	 * @param MShop_Common_Manager_Interface $localeItemManager Locale manager
	 * @param array $data Associative list of locale data
	 */
	protected function _addLocaleData( MShop_Common_Manager_Interface $localeItemManager, array $data, array $siteIds )
	{
		$this->_msg( 'Adding data for MShop locales', 1 );

		$localeItem = $localeItemManager->createItem();

		foreach( $data as $key => $dataset )
		{
			if( !isset( $siteIds[$dataset['siteid']] ) ) {
				throw new MW_Setup_Exception( sprintf( 'No ID for site for key "%1$s" found', $dataset['siteid'] ) );
			}

			$localeItem->setId( null );
			$localeItem->setSiteId( $siteIds[$dataset['siteid']] );
			$localeItem->setLanguageId( $dataset['langid'] );
			$localeItem->setCurrencyId( $dataset['currencyid'] );
			$localeItem->setPosition( $dataset['pos'] );
			$localeItem->setStatus( $dataset['status'] );

			try {
				$localeItemManager->saveItem( $localeItem );
			} catch( Exception $e ) {; } // if locale combination was already available
		}

		$this->_status( 'done' );
	}
}