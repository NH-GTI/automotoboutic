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
            'ajaxUrl' => $this->context->link->getModuleLink('carmatselector', 'view', ['action' => 'getModels']),
            'token' => Tools::getToken(false)
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
            $configurations = $this->getConfigurationByCarbody($carbody);
        }
        elseif(Tools::getValue('gamme')) {
            $gamme = (int)Tools::getValue('gamme');
            $colors = $this->getColorsByGamme($gamme);
        }
        else{
            $productArray = Tools::getValue('productArray');
            $product = $this->getProduct($productArray);
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
            WHERE id_carmatselector_brand = ' . (int)$brandId
        );
    }

    private function getVersionsByModel($modelId)
    {
        if (!$modelId) return [];
        
        return Db::getInstance()->executeS('
            SELECT id_carmatselector_version as id, name, carbody
            FROM `' . _DB_PREFIX_ . 'carmatselector_version`
            WHERE id_carmatselector_model = ' . (int)$modelId
        );
    }

    private function getGammeByCarbody($carbody)
    {
        if (!$carbody) return [];
        
        return Db::getInstance()->executeS('
            SELECT g.id_carmatselector_gamme as id, g.name, g.rating
            FROM `' . _DB_PREFIX_ . 'carmatselector_carbody_gamme_assoc` AS cga
            LEFT JOIN `' . _DB_PREFIX_ . 'carmatselector_gamme` AS g 
                ON cga.id_carmatselector_gamme = g.id_carmatselector_gamme
            WHERE cga.id_carmatselector_carbody = ' . (int)$carbody . ' 
                AND g.active = 1
        ');
    }

    private function getConfigurationByCarbody($carbody)
    {
        if (!$carbody) return [];
        
        return Db::getInstance()->executeS('
            SELECT c.id_carmatselector_configuration as id, c.name
            FROM `' . _DB_PREFIX_ . 'carmatselector_carbody_configuration_assoc` AS cca
            LEFT JOIN `' . _DB_PREFIX_ . 'carmatselector_configuration` AS c 
                ON cca.id_carmatselector_configuration = c.id_carmatselector_configuration
            WHERE cca.id_carmatselector_carbody = ' . (int)$carbody . ' 
                AND c.active = 1
        ');
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

    private function getProduct($productArray)
    {

        if (!$productArray) return [];

	$explodeProduct = explode(',', $productArray);
	
	return Db::getInstance()->executeS('
            SELECT p.id_product, cp.id_product_to_add, pl.name FROM `' . _DB_PREFIX_ . 'carmatselector_product` AS cp
	    LEFT JOIN `'. _DB_PREFIX_ . 'product` AS p ON p.reference = cp.id_product_to_add
	    LEFT JOIN `'. _DB_PREFIX_ . 'product_lang` AS pl ON pl.id_product = p.id_product
            WHERE cp.id_carmatselector_gamme = ' . (int)$explodeProduct[3] . '
            AND cp.id_carmatselector_carbody = ' . (int)$explodeProduct[4] . '
            AND cp.id_carmatselector_configuration = ' . (int)$explodeProduct[5] . '
            AND cp.id_carmatselector_color = ' . (int)$explodeProduct[6]);
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
            FROM `' . _DB_PREFIX_ . 'carmatselector_brand`'
        );
    }
}
