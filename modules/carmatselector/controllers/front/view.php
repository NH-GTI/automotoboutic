<?php
class CarmatselectorViewModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        if (Tools::getValue('ajax')) {
            $this->displayAjax();
            return;
        }

        parent::initContent();

        $data['brands'] = $this->getBrands();

        $this->context->smarty->assign([
            'carmatData' => json_encode($data),
            'ajaxUrl' => $this->context->link->getModuleLink('carmatselector', 'view'),
            'token' => Tools::getToken(false),
        ]);
        
        $this->setTemplate('module:carmatselector/views/templates/front/view.tpl');
    }

    // Add this method to handle AJAX requests
    public function displayAjax()
    {
        $models = $versions = $gammes = $configurations = $colors = [];
        $product = null;
        if(Tools::getValue('brandId')) {
            $brandId = (int)Tools::getValue('brandId');
            $models = $this->getModelsByBrand($brandId);
        }
        elseif(Tools::getValue('modelId')) {
            $modelId = (int)Tools::getValue('modelId');
            $versions = $this->getVersionsByModel($modelId);
        }
        elseif(Tools::getValue('carbody')) {
            $carbody = (int)Tools::getValue('carbody');
            $gammes = $this->getGammeByCarbody($carbody);
        }
        elseif(Tools::getValue('gammeForConfig')) {
            $gamme = (int)Tools::getValue('gammeForConfig');
            $customerGroup =  $this->context->customer ? $this->context->customer->id_default_group : 3;
            $productArray = Tools::getValue('productArray');
            $carbody = (int)Tools::getValue('carbodyForConfig');
            $configurations = $this->getConfigurationByCarbody($productArray, $carbody, $customerGroup);
        }
        elseif(Tools::getValue('gammeForColor')){
            $gamme = (int)Tools::getValue('gammeForColor');
            $colors = $this->getColorsByGamme($gamme);
        }
        elseif(Tools::getValue('getProduct')){
            $productArray = Tools::getValue('productArray');
            $customerGroup =  $this->context->customer ? $this->context->customer->id_default_group : 3;
            $product = $this->getProduct($productArray, $customerGroup);
        }
        elseif(Tools::getValue('addToCart')){
            $productArray = Tools::getValue('productArray');
            $customerGroup =  $this->context->customer ? $this->context->customer->id_default_group : 3;
            $this->addToCart($productArray, $customerGroup);
        }
        
        header('Content-Type: application/json');
        die(json_encode([
            'success' => true,
            'models' => $models,
            'versions' => $versions,
            'gammes' => $gammes,
            'configurations' => $configurations,
            'colors' => $colors,
            'product' => $product, 
        ]));
    }

    private function getModelsByBrand($brandId)
    {
        if (!$brandId) return [];
        
        return Db::getInstance()->executeS('
            SELECT id_carmatselector_model as id, name
            FROM `' . _DB_PREFIX_ . 'carmatselector_model`
            WHERE id_carmatselector_brand = ' . (int)$brandId . ' AND active = 1
            ORDER BY name ASC'
        );
    }

    private function getVersionsByModel($modelId)
    {
        if (!$modelId) return [];
        
        return Db::getInstance()->executeS('
            SELECT cv.id_carmatselector_version as id, cv.name, cv.carbody, cc.description as carbody_name, cv.gabarit
            FROM `' . _DB_PREFIX_ . 'carmatselector_version` AS cv
            LEFT JOIN `' . _DB_PREFIX_ . 'carmatselector_carbody` AS cc ON cc.id_carmatselector_carbody = cv.carbody
            WHERE id_carmatselector_model = ' . (int)$modelId . ' AND cv.active = 1
            ORDER BY cv.name ASC'
        );
    }

    private function getGammeByCarbody($carbody)
    {
        if (!$carbody) return [];

        $gammes = Db::getInstance()->executeS('
            SELECT g.id_carmatselector_gamme as id, g.name, g.rating, g.description, g.carpeting, g.outline, g.material, g.undercoat
            FROM `' . _DB_PREFIX_ . 'carmatselector_carbody_gamme_assoc` AS cga
            LEFT JOIN `' . _DB_PREFIX_ . 'carmatselector_gamme` AS g ON cga.id_carmatselector_gamme = g.id_carmatselector_gamme
            WHERE cga.id_carmatselector_carbody = ' . (int)$carbody . ' 
                AND g.active = 1
        ');

        $gammeColors = Db::getInstance()->executeS('
            SELECT cc.id_carmatselector_color as id, cc.name, cc.hex_color, cga.id_carmatselector_gamme as id_gamme
            FROM `' . _DB_PREFIX_ . 'carmatselector_color` AS cc
            LEFT JOIN `' . _DB_PREFIX_ . 'carmatselector_color_gamme_assoc` AS cga ON cc.id_carmatselector_color = cga.id_carmatselector_color
            WHERE cc.active = 1
        ');

        return [
            'gammes' => $gammes,
            'gammeColors' => $gammeColors,
        ];
    }

    private function getConfigurationByCarbody($productArray, $carbody, $customerGroup)
    {
        if (!$carbody) return [];

        $explodeProduct = explode(',', $productArray);

        $sqlConfigs = Db::getInstance()->executeS('
            SELECT c.id_carmatselector_configuration as id, c.name, GROUP_CONCAT(cp.id_product_to_add) as products
            FROM `' . _DB_PREFIX_ . 'carmatselector_carbody_configuration_assoc` AS cca
            LEFT JOIN `' . _DB_PREFIX_ . 'carmatselector_configuration` AS c ON cca.id_carmatselector_configuration = c.id_carmatselector_configuration
            LEFT JOIN ps_carmatselector_product AS cp ON cp.id_carmatselector_configuration = c.id_carmatselector_configuration
            WHERE cca.id_carmatselector_carbody = ' . (int)$carbody . ' 
            AND cp.id_carmatselector_gamme = ' . (int)$explodeProduct[0] . '
            AND c.active = 1
            GROUP BY c.id_carmatselector_configuration, c.name
        ');

        foreach ($sqlConfigs as &$config) {
            $products = explode(',', $config['products']);    
            // var_dump($products);
            $sql = '
                SELECT sp.price , t.rate
                FROM `' . _DB_PREFIX_ . 'carmatselector_product` AS cp
                LEFT JOIN `' . _DB_PREFIX_ . 'product` AS p ON p.reference = cp.id_product_to_add
                LEFT JOIN `' . _DB_PREFIX_ . 'specific_price` AS sp ON sp.id_product = p.id_product
                LEFT JOIN `' . _DB_PREFIX_ . 'tax_rule` AS tr ON tr.id_tax_rules_group = p.id_tax_rules_group
                LEFT JOIN `' . _DB_PREFIX_ . 'tax` AS t ON t.id_tax = tr.id_tax
                WHERE cp.id_carmatselector_gamme = ' . (int)$explodeProduct[0] . '
                AND cp.id_carmatselector_carbody = ' . (int)$explodeProduct[1] . '
                AND cp.id_product_to_add = "' . $products[array_rand($products)] . '"
                AND sp.id_group = ' . (int)$customerGroup . '
                AND tr.id_country = 8';
            $result = Db::getInstance()->executeS($sql);

            $config['price'] = $result ? $result[0]['price']: 0;
            $config['rate'] = $result ? $result[0]['rate']: 0;
        }
        
        return $sqlConfigs;
    }

    private function getColorsByGamme($gamme)
    {
        if (!$gamme) return [];

        return Db::getInstance()->executeS('
            SELECT c.id_carmatselector_color as id, c.name
            FROM `' . _DB_PREFIX_ . 'carmatselector_color_gamme_assoc` AS cca
            LEFT JOIN `' . _DB_PREFIX_ . 'carmatselector_color` AS c 
                ON cca.id_carmatselector_color = c.id_carmatselector_color
            WHERE cca.id_carmatselector_gamme = ' . (int)$gamme . ' 
                AND c.active = 1
        ');
    }

    private function getProduct($productArray, $customerGroup){
        $explodeProduct = explode('||', $productArray);

        return Db::getInstance()->executeS('
            SELECT p.id_product, cp.id_product_to_add, pl.name, sp.price , t.rate
            FROM `' . _DB_PREFIX_ . 'carmatselector_product` AS cp
            LEFT JOIN `' . _DB_PREFIX_ . 'product` AS p ON p.reference = cp.id_product_to_add
            LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` AS pl ON pl.id_product = p.id_product
            LEFT JOIN `' . _DB_PREFIX_ . 'specific_price` AS sp ON sp.id_product = p.id_product
            LEFT JOIN `' . _DB_PREFIX_ . 'tax_rule` AS tr ON tr.id_tax_rules_group = p.id_tax_rules_group
            LEFT JOIN `' . _DB_PREFIX_ . 'tax` AS t ON t.id_tax = tr.id_tax
            WHERE cp.id_carmatselector_gamme = ' . (int)$explodeProduct[5] . '
            AND cp.id_carmatselector_carbody = ' . (int)$explodeProduct[7] . '
            AND cp.id_carmatselector_configuration = ' . (int)$explodeProduct[9] . '
            AND cp.id_carmatselector_color = ' . (int)$explodeProduct[11] . '
            AND sp.id_group = ' . (int)$customerGroup . '
            AND tr.id_country = 8');
    }

    private function addToCart($productArray, $customerGroup)
    {
        $this->debug('Start', ['productArray' => $productArray]);
        
        if (!$productArray) return [];
        $explodeProduct = explode('||', $productArray);
        
        // Get product info
        $sql = 'SELECT p.id_product, cp.id_product_to_add, pl.name, sp.price, t.rate
            FROM `' . _DB_PREFIX_ . 'carmatselector_product` AS cp
            LEFT JOIN `' . _DB_PREFIX_ . 'product` AS p ON p.reference = cp.id_product_to_add
            LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` AS pl ON pl.id_product = p.id_product
            LEFT JOIN `' . _DB_PREFIX_ . 'specific_price` AS sp ON sp.id_product = p.id_product
            LEFT JOIN `' . _DB_PREFIX_ . 'tax_rule` AS tr ON tr.id_tax_rules_group = p.id_tax_rules_group
            LEFT JOIN `' . _DB_PREFIX_ . 'tax` AS t ON t.id_tax = tr.id_tax
            WHERE cp.id_carmatselector_gamme = ' . (int)$explodeProduct[5] . '
            AND cp.id_carmatselector_carbody = ' . (int)$explodeProduct[7] . '
            AND cp.id_carmatselector_configuration = ' . (int)$explodeProduct[9] . '
            AND cp.id_carmatselector_color = ' . (int)$explodeProduct[11] . '
            AND sp.id_group = ' . (int)$customerGroup . '
            AND tr.id_country = 8';
        
        $this->debug('Product SQL', ['sql' => $sql]);
        $product = Db::getInstance()->getRow($sql);
        $this->debug('Product Result', ['product' => $product]);
        
        if(!$product){
            $this->debug('Product Not Found', []);
            return false;
        }
    
        // Ensure cart exists
        if (empty($this->context->cart->id)) {
            $this->debug('Creating New Cart', []);
            $this->context->cart = new Cart();
            $this->context->cart->id_lang = (int)$this->context->language->id;
            $this->context->cart->id_currency = (int)$this->context->cookie->id_currency;
            $this->context->cart->id_customer = (int)$this->context->customer->id;
            $cartAddResult = $this->context->cart->add();
            $this->debug('Cart Creation Result', ['result' => $cartAddResult, 'cart_id' => $this->context->cart->id]);
            $this->context->cookie->__set('id_cart', $this->context->cart->id);
        } else {
            $this->debug('Using Existing Cart', ['cart_id' => $this->context->cart->id]);
        }
        
        // Create customization data
        $customization_value = $explodeProduct[1]; // marque
        $customization_value .= ' / ' . $explodeProduct[3]; // version
        $customization_value .= ' / Gamme ' . $explodeProduct[6]; // gamme
        $customization_value .= ' / ' . $explodeProduct[10]; // configuration
        $customization_value .= ' / ' . $explodeProduct[12]; // color
        $customization_value .= ' / ~~' . $explodeProduct[4] . '~~'; // gabarit
        
        // Insert customization
        $customizationSql = 'INSERT INTO `'._DB_PREFIX_.'customization` 
            (`id_product_attribute`, `id_cart`, `id_product`, `quantity`, `in_cart`) 
            VALUES (0, '.(int)$this->context->cart->id.', '.(int)$product['id_product'].', 1, 1)';
        
        $this->debug('Customization SQL', ['sql' => $customizationSql]);
        $customizationResult = Db::getInstance()->execute($customizationSql);
        $this->debug('Customization Insert', ['result' => $customizationResult]);
        
        if (!$customizationResult) {
            $this->debug('Customization Insert Failed', []);
            return false;
        }
        
        $id_customization = Db::getInstance()->Insert_ID();
        $this->debug('Customization ID', ['id' => $id_customization]);
        
        // Get/create customization field
        $customization_field = (int)Db::getInstance()->getValue('
            SELECT `id_customization_field`
            FROM `'._DB_PREFIX_.'customization_field`
            WHERE `id_product` = ' . (int)$product['id_product'] . ' AND `type` = 1
        ');
        
        $this->debug('Existing Customization Field', ['id' => $customization_field]);
        
        if (empty($customization_field)) {
            $cfSql = 'INSERT INTO `'._DB_PREFIX_.'customization_field` 
                (`id_product`, `type`, `required`, `is_module`) 
                VALUES ('.(int)$product['id_product'].', 1, 0, 1)';
            
            $this->debug('Create Customization Field SQL', ['sql' => $cfSql]);
            $cfResult = Db::getInstance()->execute($cfSql);
            $this->debug('Create Customization Field Result', ['result' => $cfResult]);
            
            $customization_field = (int)Db::getInstance()->Insert_ID();
            $this->debug('New Customization Field ID', ['id' => $customization_field]);
            
            // Add field name for each language
            foreach (Language::getLanguages(false) as $language) {
                $langSql = 'INSERT INTO `'._DB_PREFIX_.'customization_field_lang` 
                    (`id_customization_field`, `id_lang`, `id_shop`, `name`) 
                    VALUES ('.$customization_field.', '.(int)$language['id_lang'].', '
                    .(int)$this->context->shop->id.', "Détails")';
                
                $langResult = Db::getInstance()->execute($langSql);
                $this->debug('Field Lang Insert', [
                    'lang' => $language['id_lang'], 
                    'result' => $langResult
                ]);
            }
        }
        
        // Add customization data
        $customDataSql = 'INSERT INTO `'._DB_PREFIX_.'customized_data` 
            (`id_customization`, `type`, `index`, `value`) 
            VALUES ('.$id_customization.', 1, '.$customization_field.', "'
            .pSQL($customization_value).'")';
        
        $this->debug('Custom Data SQL', ['sql' => $customDataSql]);
        $customDataResult = Db::getInstance()->execute($customDataSql);
        $this->debug('Custom Data Insert', ['result' => $customDataResult]);
        
        if (!$customDataResult) {
            $this->debug('Custom Data Insert Failed', []);
            return false;
        }
        
        // Add to cart
        $this->debug('Adding To Cart', [
            'id_product' => (int)$product['id_product'],
            'id_customization' => $id_customization
        ]);
        
        $updateResult = $this->context->cart->updateQty(
            1, 
            (int)$product['id_product'], 
            0, 
            (int)$id_customization,
            'up'
        );
        
        $this->debug('UpdateQty Result', ['result' => $updateResult]);
        
        // Force cart update
        $cartUpdateResult = $this->context->cart->update();
        $this->debug('Cart Update Result', ['result' => $cartUpdateResult]);
        
        return $product;
    }
    
    private function debug($step, $data) {
        file_put_contents(
            _PS_ROOT_DIR_.'/debug_cart.log', 
            date('Y-m-d H:i:s') . " | $step | " . json_encode($data) . "\n", 
            FILE_APPEND
        );
    }

    /**
     * Get ID wishlist by Token
     *
     * @param string $token
     *
     * @return array Results
     *
     * @throws PrestaShopException
     */
    public static function getBrands()
    {
        return Db::getInstance((bool) _PS_USE_SQL_SLAVE_)->executeS('
            SELECT id_carmatselector_brand as id, name as name
            FROM `' . _DB_PREFIX_ . 'carmatselector_brand`
            Order by name asc'
        );
    }
}