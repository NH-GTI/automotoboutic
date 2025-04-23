<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once 'IWSTDImportDB.php';

class IWSTDImportModel extends AdminImportController
{
    // model used for import category (copy or override if necessary)
    const CATEGORY_ENTITY = 0;

    protected function categoryFields()
    {
        return array(
            'ID' => null,
            'Active (0/1)' => null,
            'Name' => null,
            'Parent category' => null,
            'Root category (0/1)' => null,
            'Description' => null,
            'Meta title' => null,
            'Meta keywords' => null,
            'Meta description' => null,
            'URL rewritten' => null,
            'Image URL' => null,
            'ID / Name of shop' => null,
        );
    }

    // model used for import product (copy or override if necessary)
    const PRODUCT_ENTITY = 1;

    protected function productFields()
    {
        return array(
        'ID' => null,
        'Active (0/1)' => null,
        'Name *' => null,
        'Categories (x,y,z...)' => null,
        'Price tax excluded' => null,
        'Price tax included' => null,
        'Tax rules ID' => null,
        'Wholesale price' => null,
        'On sale (0/1)' => null,
        'Discount amount' => null,
        'Discount percent' => null,
        'Discount from (yyyy-mm-dd)' => null,
        'Discount to (yyyy-mm-dd)' => null,
        'Reference #' => null,
        'Supplier reference #' => null,
        'Supplier' => null,
        'Manufacturer' => null,
        'EAN13' => null,
        'UPC' => null,
        'Ecotax' => null,
        'Width' => null,
        'Height' => null,
        'Depth' => null,
        'Weight' => null,
        'Quantity' => null,
        'Minimal quantity' => null,
        'Visibility' => null,
        'Additional shipping cost' => null,
        'Unity' => null,
        'Unit price' => null,
        'Short description' => null,
        'Description' => null,
        'Tags (x,y,z...)' => null,
        'Meta title' => null,
        'Meta keywords' => null,
        'Meta description' => null,
        'URL rewritten' => null,
        'Text when in stock' => null,
        'Text when backorder allowed' => null,
        'Available for order (0 = No, 1 = Yes)' => null,
        'Product available date' => null,
        'Product creation date' => null,
        'Show price (0 = No, 1 = Yes)' => null,
        'Image URLs (x,y,z...)' => null,
        'Delete existing images (0 = No, 1 = Yes)' => null,
        'Feature(Name:Value:Position)' => null,
        'Available online only (0 = No, 1 = Yes)' => null,
        'Condition' => null,
        'Customizable (0 = No, 1 = Yes)' => null,
        'Uploadable files (0 = No, 1 = Yes)' => null,
        'Text fields (0 = No, 1 = Yes)' => null,
        'Out of stock' => null,
        'ID / Name of shop' => null,
        'Advanced stock management' => null,
        'Depends On Stock' => null,
        'Warehouse' => null,
        );
    }

    // model used for product attributes (copy or override if necessary)
    const ATTRIBUTE_ENTITY = 2;

    protected function attributeFields()
    {
        return array(
        'Product ID*' => null,
        'Product Reference' => null,
        'Attribute (Name:Type:Position)*' => null,    //with type : radio, select, color
        'Value (Value:Position)*' => null,
        'Supplier reference' => null,
        'Reference' => null,
        'EAN13' => null,
        'UPC' => null,
        'Wholesale price' => null,
        'Impact on price' => null,
        'Ecotax' => null,
        'Quantity' => null,
        'Minimal quantity' => null,
        'Impact on weight' => null,
        'Default (0 = No	 1 = Yes)' => null,
        'Combination available date' => null,
        'Image position' => null,
        'Image URL' => null,
        'Delete existing images (0 = No	 1 = Yes)' => null,
        'ID / Name of shop' => null,
        'Advanced Stock Managment' => null,
        'Depends on stock' => null,
        'Warehouse' => null,
        );
    }

    // model used for customer (copy or override if necessary)
    const CUSTOMER_ENTITY = 3;

    protected function customerFields()
    {
        return array(
        'ID' => null,    // unique with this field
        'Active (0/1)' => null,
        'Titles ID (Mr = 1, Ms = 2, else 0)' => null,
        'E-mail *' => null,
        'Password *' => null,
        'Birthday (yyyy-mm-dd)' => null,
        'Last Name *' => null,
        'First Name *' => null,
        'Newsletter (0/1)' => null,
        'Opt-in (0/1)' => null,
        'Groups (x,y,z...)' => null,
        'Default group ID' => null,
        'ID / Name of shop' => null,
        );
    }

    const ADDRESS_ENTITY = 4;

    protected function addressFields()
    {
        return array(
        'ID' => null,    // unique with this field
        'Alias *' => null,
        'Active (0/1)' => null,
        'Customer email *' => null,
        'Customer ID' => null,
        'Manufacturer' => null,
        'Supplier' => null,
        'Company' => null,
        'Last Name *' => null,
        'First Name *' => null,
        'Address 1 *' => null,
        'Address 2' => null,
        'Zip/postal code *' => null,
        'City *' => null,
        'Country *' => null,
        'State' => null,
        'Other' => null,
        'Phone' => null,
        'Mobile Phone' => null,
        'VAT number' => null,
        'DNI/NIF/NIE' => null,
        );
    }

    const MANUFACTURER_ENTITY = 5;

    protected function manufacturerFields()
    {
        return array(
        'ID' => null,
        'Active (0/1)' => null,
        'Name' => null,
        'Description' => null,
        'Short description' => null,
        'Meta title' => null,
        'Meta keywords' => null,
        'Meta description' => null,
        'Image URL' => null,
        'ID / Name of group shop' => null,
        );
    }

    const SUPPLIER_ENTITY = 6;

    protected function supplierFields()
    {
        return $this->manufacturerFields();
    }

    const ALIAS_ENTITY = 7;

    protected function aliasFields()
    {
        return array(
        'ID' => null,
        'Alias *' => null,
        'Search *' => null,
        'Active' => null,
        );
    }

    protected function receiveTab()
    {
        self::$column_mask = array();
        $nb = 0;
        foreach (array_keys($this->available_fields) as $key) {
            if ($key != 'no') {
                self::$column_mask[$key] = $nb++;
            }
        }
    }
}
