<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @package Controller
 * @subpackage Frontend
 */


/**
 * Interface for catalog frontend controllers.
 *
 * @package Controller
 * @subpackage Frontend
 */
interface Controller_Frontend_Catalog_Interface
	extends Controller_Frontend_Common_Interface
{
	/**
	 * Returns the manager for the given name
	 *
	 * @param string $name Name of the manager
	 * @return MShop_Common_Manager_Interface Manager object
	 */
	public function createManager( $name );


	/**
	 * Returns the default catalog filter
	 *
	 * @return MW_Common_Criteria_Interface Criteria object for filtering
	 * @since 2015.08
	 */
	public function createCatalogFilter();


	/**
	 * Returns the list of categries that are in the path to the root node including the one specified by its ID.
	 *
	 * @param integer $id Category ID to start from, null for root node
	 * @param string[] $domains Domain names of items that are associated with the categories and that should be fetched too
	 * @return array Associative list of items implementing MShop_Catalog_Item_Interface with their IDs as keys
	 * @since 2015.08
	 */
	public function getCatalogPath( $id, array $domains = array( 'text', 'media' ) );


	/**
	 * Returns the hierarchical catalog tree starting from the given ID.
	 *
	 * @param integer|null $id Category ID to start from, null for root node
	 * @param string[] $domains Domain names of items that are associated with the categories and that should be fetched too
	 * @param integer $level Constant from MW_Tree_Manager_Abstract for the depth of the returned tree, LEVEL_ONE for
	 * 	specific node only, LEVEL_LIST for node and all direct child nodes, LEVEL_TREE for the whole tree
	 * @param MW_Common_Criteria_Interface|null $search Optional criteria object with conditions
	 * @return MShop_Catalog_Item_Interface Catalog node, maybe with children depending on the level constant
	 * @since 2015.08
	 */
	public function getCatalogTree( $id = null, array $domains = array( 'text', 'media' ),
		$level = MW_Tree_Manager_Abstract::LEVEL_TREE, MW_Common_Criteria_Interface $search = null );


	/**
	 * Returns the aggregated count of products from the index for the given key.
	 *
	 * @param MW_Common_Criteria_Interface $filter Critera object which contains the filter conditions
	 * @param string $key Search key to aggregate for, e.g. "catalog.index.attribute.id"
	 * @return array Associative list of key values as key and the product count for this key as value
	 * @since 2015.08
	 */
	public function aggregateIndex( MW_Common_Criteria_Interface $filter, $key );


	/**
	 * Returns the given search filter with the conditions attached for filtering by category.
	 *
	 * @param MW_Common_Criteria_Interface $search Criteria object used for product search
	 * @param string $catid Selected category by the user
	 * @return MW_Common_Criteria_Interface Criteria object containing the conditions for searching
	 * @since 2015.08
	 */
	public function addIndexFilterCategory( MW_Common_Criteria_Interface $search, $catid );


	/**
	 * Returns the given search filter with the conditions attached for filtering texts.
	 *
	 * @param MW_Common_Criteria_Interface $search Criteria object used for product search
	 * @param string $input Search string entered by the user
	 * @param string $listtype List type of the text associated to the product, usually "default"
	 * @return MW_Common_Criteria_Interface Criteria object containing the conditions for searching
	 * @since 2015.08
	 */
	public function addIndexFilterText( MW_Common_Criteria_Interface $search, $input, $listtype = 'default' );


	/**
	 * Returns the default index filter.
	 *
	 * @param string|null $sort Sortation of the product list like "name", "code", "price" and "position", null for no sortation
	 * @param string $direction Sort direction of the product list ("+", "-")
	 * @param integer $start Position in the list of found products where to begin retrieving the items
	 * @param integer $size Number of products that should be returned
	 * @param string $listtype Type of the product list, e.g. default, promotion, etc.
	 * @return MW_Common_Criteria_Interface Criteria object containing the conditions for searching
	 * @since 2015.08
	 */
	public function createIndexFilter( $sort = null, $direction = '+', $start = 0, $size = 100, $listtype = 'default' );


	/**
	 * Returns a index filter for the given category ID.
	 *
	 * @param integer $catid ID of the category to get the product list from
	 * @param string $sort Sortation of the product list like "name", "price" and "position"
	 * @param string $direction Sort direction of the product list ("asc", "desc")
	 * @param integer $start Position in the list of found products where to begin retrieving the items
	 * @param integer $size Number of products that should be returned
	 * @param string $listtype Type of the product list, e.g. default, promotion, etc.
	 * @return MW_Common_Criteria_Interface Criteria object containing the conditions for searching
	 * @since 2015.08
	 */
	public function createIndexFilterCategory( $catid, $sort = 'position', $direction = 'asc', $start = 0, $size = 100, $listtype = 'default' );


	/**
	 * Returns the index filter for the given search string.
	 *
	 * @param string $input Search string entered by the user
	 * @param string $sort Sortation of the product list like "name", "price" and "relevance"
	 * @param string $direction Sort direction of the product list ("asc", "desc", but not for relevance )
	 * @param integer $start Position in the list of found products where to begin retrieving the items
	 * @param integer $size Number of products that should be returned
	 * @param string $listtype List type of the text associated to the product, usually "default"
	 * @return MW_Common_Criteria_Interface Criteria object containing the conditions for searching
	 * @since 2015.08
	 */
	public function createIndexFilterText( $input, $sort = 'relevance', $direction = 'asc', $start = 0, $size = 100, $listtype = 'default' );


	/**
	 * Returns the products from the index filtered by the given criteria object.
	 *
	 * @param MW_Common_Criteria_Interface $filter Critera object which contains the filter conditions
	 * @param string[] $domains Domain names of items that are associated with the products and that should be fetched too
	 * @param integer &$total Parameter where the total number of found products will be stored in
	 * @return array Ordered list of product items implementing MShop_Product_Item_Interface
	 * @since 2015.08
	 */
	public function getIndexItems( MW_Common_Criteria_Interface $filter, array $domains = array( 'media', 'price', 'text' ), &$total = null );


	/**
	 * Returns the product item for the given ID if it's available
	 *
	 * @param array $ids List of product IDs
	 * @param array $domains Domain names of items that are associated with the products and that should be fetched too
	 * @return array List of product items implementing MShop_Product_Item_Interface
	 * @since 2015.08
	 */
	public function getProductItems( array $ids, array $domains = array( 'media', 'price', 'text' ) );


	/**
	 * Returns text filter for the given search string.
	 *
	 * @param string $input Search string entered by the user
	 * @param string|null $sort Sortation of the product list like "name" and "relevance", null for no sortation
	 * @param string $direction Sort direction of the product list ("asc", "desc")
	 * @param integer $start Position in the list of found products where to begin retrieving the items
	 * @param integer $size Number of products that should be returned
	 * @param string $listtype List type of the text associated to the product, usually "default"
	 * @param string $type Type of the text like "name", "short", "long", etc.
	 * @return MW_Common_Criteria_Interface Criteria object containing the conditions for searching
	 */
	public function createTextFilter( $input, $sort = null, $direction = 'desc', $start = 0, $size = 25, $listtype = 'default', $type = 'name' );


	/**
	 * Returns an list of product text strings matched by the filter.
	 *
	 * @param MW_Common_Criteria_Interface $filter Critera object which contains the filter conditions
	 * @return array Associative list of the product ID as key and the product text as value
	 */
	public function getTextList( MW_Common_Criteria_Interface $filter );
}
