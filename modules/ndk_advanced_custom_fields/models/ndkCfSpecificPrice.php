<?php
/**
 *  Tous droits réservés NDKDESIGN
 *
 *  @author    Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2017 Hendrik Masson
 *  @license   Tous droits réservés
*/

class NdkCfSpecificPrice extends ObjectModel 
{
	
	public $id_ndk_customization_field_specific_price;
	public $id_ndk_customization_field;
	public $id_ndk_customization_field_value;
	public $reduction;
	public $reduction_type;
	public $from_quantity;
	
	
		
	public static $definition = array(
		'table' => 'ndk_customization_field_specific_price',
		'primary' => 'id_ndk_customization_field_specific_price',
		'fields' => array(
			'id_ndk_customization_field' => array('type' => self::TYPE_INT, 'required' => false),
			'id_ndk_customization_field_value' =>	array('type' => self::TYPE_INT, 'required' => false),
			'reduction' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => false),
			'reduction_type' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'from_quantity' =>	array('type' => self::TYPE_INT, 'required' => false),
		)
	);


	public static function getSpecificPrices($id_field, $id_value = 0, $quantity = 0, $with_taxes = 0, $id_product = 0)
	{
			$where_qtty = ' ORDER BY sp.from_quantity ';
			if((int)$quantity > 0)
				$where_qtty = ' AND sp.`from_quantity` <= '.(int)$quantity.' ORDER BY sp.from_quantity desc';
			
			$sql = '
				SELECT *
				FROM `'._DB_PREFIX_.'ndk_customization_field_specific_price` sp
				WHERE sp.`id_ndk_customization_field` = '.(int)$id_field. ($id_value > 0 ? ' AND sp.`id_ndk_customization_field_value` = '.(int)$id_value : '').
				$where_qtty;
			$result = Db::getInstance()->executeS($sql);
			
			if(sizeof($result) > 0)
			{
				if($with_taxes)
				{
					$context = Context::getContext();
					$customer_group = $context->customer->getGroups();
					$customer_group[] = 0;
					$id_address = (int)Context::getContext()->cart->id_address_invoice;
					$address = Address::initialize($id_address, true);
					$tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)$id_product, Context::getContext()));
					$product_tax_calculator = $tax_manager->getTaxCalculator();
					$usetax = Group::getPriceDisplayMethod(Group::getPriceDisplayMethod(Context::getContext()->customer->id_default_group));
					$usetax = Product::$_taxCalculationMethod == PS_TAX_INC;
					$i = 0;
					foreach($result as $row)
					{
						if($row['reduction_type'] == 'amount' && $usetax)
						{
							$result[$i]['reduction'] = $product_tax_calculator->addTaxes( $row['reduction'] );
						}
					$i++;
							
					}
				
				}
				
				return $result;
			}
			else 
			return false;
	}
	
	public static function getSpecificPricesNamed($id_field, $id_value = 0, $quantity = 0, $id_product=0)
	{
			$where_qtty = ' ORDER BY sp.from_quantity ';
			if((int)$quantity > 0)
				$where_qtty = ' AND sp.`from_quantity` <= '.(int)$quantity.' ORDER BY sp.from_quantity desc';
			
			$sql = '
				SELECT *, vl.value 
				FROM `'._DB_PREFIX_.'ndk_customization_field_specific_price` sp 
										LEFT JOIN '._DB_PREFIX_.'ndk_customization_field_value_lang vl ON (vl.id_ndk_customization_field_value = sp.id_ndk_customization_field_value AND vl.id_lang = '.(int)Context::getContext()->language->id.') 
				WHERE sp.`id_ndk_customization_field` = '.(int)$id_field. ($id_value > 0 ? ' AND sp.`id_ndk_customization_field_value` = '.(int)$id_value : '').
				$where_qtty;
			$result = Db::getInstance()->executeS($sql);
			
			if(sizeof($result) > 0)
			{
				$context = Context::getContext();
				$customer_group = $context->customer->getGroups();
				$customer_group[] = 0;
				$id_address = (int)Context::getContext()->cart->id_address_invoice;
				$address = Address::initialize($id_address, true);
				$tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)$id_product, Context::getContext()));
				$product_tax_calculator = $tax_manager->getTaxCalculator();
				$usetax = Group::getPriceDisplayMethod(Group::getPriceDisplayMethod(Context::getContext()->customer->id_default_group));
				$usetax = Product::$_taxCalculationMethod == PS_TAX_INC;
				$i = 0;
				foreach($result as $row)
				{
					if($row['reduction_type'] == 'amount' && $usetax)
					{
						$result[$i]['reduction'] = $product_tax_calculator->addTaxes( $row['reduction'] );
					}
					$i++;
							
				}
				return $result;
			}
				
			else 
			return false;
	}
	
	public static function getIdByPrimary($id_ndk_customization_field_value = 0, $from_quantity = 0)
	{
		if((int)$id_ndk_customization_field_value == 0 || $from_quantity = 0)
		return 0;
		
		$id_lang = Context::getContext()->language->id;
		$fields = Db::getInstance()->getValue('
				SELECT `id_ndk_customization_field_specific_price` as id
				FROM `'._DB_PREFIX_.'ndk_customization_field_specific_price`
				WHERE id_ndk_customization_field_value = '.(int)$id_ndk_customization_field_value.' AND from_quantity = '.(int)$from_quantity);
		return $fields;
	}
}
?>