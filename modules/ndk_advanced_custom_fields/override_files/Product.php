<?php
/**
 *  Tous droits réservés NDKDESIGN
 *
 *  @author    Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*/

class Product extends ProductCore{

	
		public function hasAllRequiredCustomizableFields(Context $context = null)
			{
				if(Context::getContext()->controller->php_self == 'cart' )
					return true;
				
				if (!Customization::isFeatureActive())
					return true;
				if (!$context)
					$context = Context::getContext();
		
				$fields = $context->cart->getProductCustomization($this->id, null, true);
				if (($required_fields = $this->getRequiredCustomizableFields()) === false)
					return false;
		
				$fields_present = array();
				foreach ($fields as $field)
					$fields_present[] = array('id_customization_field' => $field['index'], 'type' => $field['type']);
		
				if (is_array($required_fields) && count($required_fields))
					foreach ($required_fields as $required_field)
						if (!in_array($required_field, $fields_present))
							return false;
				return true;
			}
			

}

 ?>