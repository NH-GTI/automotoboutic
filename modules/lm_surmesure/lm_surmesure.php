<?php
if (!defined('_PS_VERSION_'))
	exit;

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureColors.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureConfigurations.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammes.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureGammesImages.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureModCodeGabarit.php');

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureBachesCategories.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureBachesMarques.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureBachesModeles.php');

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureTelephoneMarques.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureTelephoneModeles.php');

include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureHoussesMarques.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureHoussesModeles.php');
include_once (_PS_MODULE_DIR_ . 'lm_surmesure/classes/LmSurmesureHoussesTaillesModeles.php');

class Lm_Surmesure extends Module implements WidgetInterface
{
    public $_id_category = 17; // 33 Categories
    public $_cat_telephones = array(76, 77); // array(72, 53); Categories
    public $_cat_baches = array(78, 79, 80); // array(64, 65, 66); Categories
    public $_id_carro = 60; // Feature Carrosserie 60
    
    public $_id_gam = 61; // Feature Gamme
    public $_id_gam_basique = 3262; // Feature value Gamme basique
    public $_id_gam_elite_carat = array(3264, 50171); // Feature value Gamme elite, carat
    
    public $_id_type = 59; // Feature type
    public $_id_value_basique = 3257; // Feature value Type Basique
    
    public $_id_conf = 54; // Feature Configuration
    public $_id_basique_conf = 515; // Feature Value Only for the gamme basique
    
    public $_id_mod = 1; // Feature Model
    
    public $_id_fix = 25; // Feature Fixation
    
    public $_id_color = 11; // Feature Color
    
    public $_id_marque = 49; // Feature Brand
    public $_id_family = 88; // Feature Brand
    public $_featured_brand_ids = array(1105, 1102, 1076, 1116); // Feature Values featured brands
    
    public $errors = array();
    
	public function __construct()
	{
        $this->name = 'lm_surmesure';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Lemon-interactive';
        $this->need_instance = 0;
        $this->secure_key = Tools::encrypt($this->name);
        $this->bootstrap = true;

        parent::__construct();
        
        $this->displayName = $this->getTranslator()->trans('Lemon-interactive : Sur Mesure', array(), 'Modules.Surmesure.Admin');
        $this->description = $this->trans('Allow customers to customize the products.', array(), 'Modules.Surmesure.Admin');

        $this->ps_versions_compliancy = array('min' => '1.7.1.1', 'max' => _PS_VERSION_);
	}

    /**
     * @see Module::install()
     */
    public function install()
    {
        if (parent::install()) {
            $result = $this->createTables();
            // version specific
            $result &= $this->addTab("AdminLm_SurmesureCustomCarpets", "Tapis sur mesure", "AdminCatalog");
            $result &= $this->addTab("AdminLm_SurmesureCustomArticles", "Articles", "AdminLm_SurmesureCustomCarpets");
            $result &= $this->addTab("AdminLm_SurmesureCustomModels", "Modèles", "AdminLm_SurmesureCustomCarpets");
            $result &= $this->addTab("AdminLm_SurmesureCustomCarrosConfig", "Carrosseries / Configuration", "AdminLm_SurmesureCustomCarpets");
            $result &= $this->addTab("AdminLm_SurmesureCustomCarrosGammes", "Carrosseries / Gammes", "AdminLm_SurmesureCustomCarpets");
            $result &= $this->addTab("AdminLm_SurmesureCustomGammesColors", "Gammes / Couleurs", "AdminLm_SurmesureCustomCarpets");
            $result &= $this->addTab("AdminLm_SurmesureCustomGammes", "Gammes", "AdminLm_SurmesureCustomCarpets");
            $result &= $this->addTab("AdminLm_SurmesureCustomColors", "Couleurs", "AdminLm_SurmesureCustomCarpets");
            $result &= $this->addTab("AdminLm_SurmesureCustomConfigurations", "Configurations", "AdminLm_SurmesureCustomCarpets");
            $result &= $this->addTab("AdminLm_SurmesureCustomGammeImages", "Images gammes", "AdminLm_SurmesureCustomCarpets");
            $result &= $this->registerHook('moduleRoutes');
            /*$result &= $this->addTab("AdminLm_SurmesureSlipcover", "Housses de siège auto", "AdminCatalog");
            $result &= $this->addTab("AdminLm_SurmesureSlipcoverBrands", "Marques", "AdminLm_SurmesureSlipcover");
            $result &= $this->addTab("AdminLm_SurmesureSlipcoverModels", "Modèles", "AdminLm_SurmesureSlipcover");
            $result &= $this->addTab("AdminLm_SurmesureSlipcoverModelsSizes", "Modèles / Tailles", "AdminLm_SurmesureSlipcover");
            
            $result &= $this->addTab("AdminLm_SurmesureChargers", "Chargeurs de téléphone", "AdminCatalog");
            $result &= $this->addTab("AdminLm_SurmesureChargersBrands", "Marques", "AdminLm_SurmesureChargers");
            $result &= $this->addTab("AdminLm_SurmesureChargersModels", "Modèles", "AdminLm_SurmesureChargers");
            $result &= $this->addTab("AdminLm_SurmesureChargersModelsProducts", "Modèles / Produits", "AdminLm_SurmesureChargers");
            
            $result &= $this->addTab("AdminLm_SurmesureBaches", "Bâches auto", "AdminCatalog");
            $result &= $this->addTab("AdminLm_SurmesureBachesBrands", "Marques", "AdminLm_SurmesureBaches");
            $result &= $this->addTab("AdminLm_SurmesureBachesModels", "Modèles", "AdminLm_SurmesureBaches");
            $result &= $this->addTab("AdminLm_SurmesureBachesCategories", "Catégories", "AdminLm_SurmesureBaches");
            $result &= $this->addTab("AdminLm_SurmesureBachesCategoriesReferences", "Catégories / Références", "AdminLm_SurmesureBaches");*/
            return $result;
        }
        return false;
    }
    
	public function uninstall()
	{
        $result = parent::uninstall()
            && $this->deleteTables()
            && $this->deleteTab("AdminLm_SurmesureCustomArticles")
            && $this->deleteTab("AdminLm_SurmesureCustomModels")
            && $this->deleteTab("AdminLm_SurmesureCustomCarrosConfig")
            && $this->deleteTab("AdminLm_SurmesureCustomCarrosGammes")
            && $this->deleteTab("AdminLm_SurmesureCustomGammesColors")
            && $this->deleteTab("AdminLm_SurmesureCustomGammes")
            && $this->deleteTab("AdminLm_SurmesureCustomColors")
            && $this->deleteTab("AdminLm_SurmesureCustomConfigurations")
            && $this->deleteTab("AdminLm_SurmesureCustomGammeImages")
            && $this->deleteTab("AdminLm_SurmesureCustomCarpets")
            /*
            && $this->deleteTab("AdminLm_SurmesureSlipcoverBrands")
            && $this->deleteTab("AdminLm_SurmesureSlipcoverModels")
            && $this->deleteTab("AdminLm_SurmesureSlipcoverModelsSizes")
            && $this->deleteTab("AdminLm_SurmesureSlipcover")
            
            && $this->deleteTab("AdminLm_SurmesureChargersBrands")
            && $this->deleteTab("AdminLm_SurmesureChargersModels")
            && $this->deleteTab("AdminLm_SurmesureChargersModelsProducts")
            && $this->deleteTab("AdminLm_SurmesureChargers")
            
            && $this->deleteTab("AdminLm_SurmesureBachesBrands")
            && $this->deleteTab("AdminLm_SurmesureBachesModels")
            && $this->deleteTab("AdminLm_SurmesureBachesCategories")
            && $this->deleteTab("AdminLm_SurmesureBachesCategoriesReferences")
            && $this->deleteTab("AdminLm_SurmesureBaches")*/;
                     
        return $result;
	}

    /**
     * Creates tables
     */
    protected function createTables()
    {
        $res = Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'customproducts_carro_conf` (
                `id_carro` int(10) NOT NULL,
                `id_conf` int(10) NOT NULL
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'customproducts_carro_gam` (
               `id_carro` int(10) NOT NULL,
               `id_gam` int(10) NOT NULL
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'customproducts_carro_mod` (  
               `id_carro` int(10) NOT NULL,
               `id_mod` int(10) NOT NULL
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'customproducts_configurations` (         
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `id_conf` int(11) NOT NULL,
                `description` text NOT NULL,
                `image` varchar(100) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'customproducts_couleurs` (              
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `id_color` int(11) NOT NULL,
                  `image` varchar(100) NOT NULL,
                  `alias` varchar(30) NOT NULL,
                  PRIMARY KEY (`id`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'customproducts_gammes` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `sort` int(11) NOT NULL,
                `id_gamme` int(11) NOT NULL,
                `prix` float NOT NULL,
                `qualite_contour` text NOT NULL,
                `qualite_moquette` text NOT NULL,
                `qualite_materiaux` text NOT NULL,
                `sous_couche` text NOT NULL,
                `coloris` text NOT NULL,
                `plus_produit` text NOT NULL,
                `avis` text NOT NULL,
                `rating` int(11) NOT NULL,
                `alias` varchar(50) NOT NULL,
                `status` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'customproducts_gammes_couleurs` (  
                 `id_gam` int(11) NOT NULL,
                 `id_couleur` int(11) NOT NULL,
                 `images` varchar(200) NOT NULL,
                 `main_image` varchar(100) NOT NULL
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'customproducts_gammes_images` (
                   `id` int(11) NOT NULL AUTO_INCREMENT,
                   `id_gamme` int(11) NOT NULL,
                   `image` varchar(64) NOT NULL,
                   `legende` varchar(255) NOT NULL,
                   `sort` int(11) NOT NULL,
                   PRIMARY KEY (`id`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'customproducts_mod_code_gabarit` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `id_mod` int(11) NOT NULL,
                  `code_gabarit` varchar(50) NOT NULL,
                  PRIMARY KEY (`id`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'customproducts_mod_coffre` (
                `id_mod` int(11) NOT NULL,
                `coffre` int(11) NOT NULL
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'customproducts_mod_fix` (
                 `id_mod` int(10) NOT NULL,
                 `id_fix` int(10) NOT NULL
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
            
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'baches_categories` (
                 `id_categorie` int(11) NOT NULL AUTO_INCREMENT,
                 `nom` varchar(64) NOT NULL,
                 PRIMARY KEY (`id_categorie`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'baches_categories_references` (
                `id_categorie` int(11) NOT NULL,
                `reference` varchar(64) NOT NULL,
                `taille` varchar(16) NOT NULL,
                KEY `id_categorie` (`id_categorie`,`reference`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'baches_marques` (
                `id_marque` int(11) NOT NULL AUTO_INCREMENT,
                `nom` varchar(64) NOT NULL,
                `sort` int(11) NOT NULL,
                PRIMARY KEY (`id_marque`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'baches_modeles` (
                `id_modele` int(11) NOT NULL AUTO_INCREMENT,
                `nom` varchar(64) NOT NULL,
                `id_marque` int(11) NOT NULL,
                `t1` varchar(16) NOT NULL,
                `t2` varchar(16) NOT NULL,
                `t3` varchar(16) NOT NULL,
                `t4` varchar(16) NOT NULL,
                `t5` varchar(16) NOT NULL,
                `t6` varchar(16) NOT NULL,
                PRIMARY KEY (`id_modele`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'housses_marques` (
                `id_marque` int(11) NOT NULL AUTO_INCREMENT,
                `nom` varchar(64) NOT NULL,
                `sort` int(11) NOT NULL,
                PRIMARY KEY (`id_marque`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'housses_modeles` (
                `id_modele` int(11) NOT NULL AUTO_INCREMENT,
                `nom` varchar(100) NOT NULL,
                `id_marque` int(11) NOT NULL,
                PRIMARY KEY (`id_modele`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'housses_tailles_modeles` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `id_modele` int(11) NOT NULL,
                `premium_avant` varchar(10) NOT NULL,
                `premium_complet` varchar(5) NOT NULL,
                `elite_avant` varchar(5) NOT NULL,
                `elite_complet` varchar(5) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'telephone_marques` (
                `id_marque` int(11) NOT NULL AUTO_INCREMENT,
                `nom` varchar(64) NOT NULL,
                PRIMARY KEY (`id_marque`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'telephone_modeles` (
                `id_modele` int(11) NOT NULL AUTO_INCREMENT,
                `nom` varchar(64) NOT NULL,
                `id_marque` int(11) NOT NULL,
                PRIMARY KEY (`id_modele`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'telephone_modeles_products` (
                `id_modele` int(11) NOT NULL,
                `id_product` int(11) NOT NULL,
                KEY `id_modele` (`id_modele`,`id_product`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        return $res;
    }

    /**
     * deletes tables
     */
    protected function deleteTables()
    {
        return Db::getInstance()->execute('
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'customproducts_carro_conf`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'customproducts_carro_gam`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'customproducts_carro_mod`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'customproducts_configurations`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'customproducts_couleurs`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'customproducts_gammes`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'customproducts_gammes_couleurs`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'customproducts_gammes_images`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'customproducts_mod_code_gabarit`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'customproducts_mod_coffre`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'customproducts_mod_fix`;
            
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'telephone_modeles`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'telephone_marques`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'telephone_modeles_products`;
            
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'housses_modeles`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'housses_marques`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'housses_tailles_modeles`;
            
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'baches_modeles`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'baches_marques`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'baches_categories`;
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'baches_categories_references`;
        ');
    }

    /**
     * add tab
     */
    public function addTab($className, $name, $parentClassName) 
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $className;
        $tab->name = array();
        $tab->name[(int)(Configuration::get('PS_LANG_DEFAULT'))] = $this->l($name);
        $tab->module = $this->name;
        $tab->id_parent = (int)Tab::getIdFromClassName($parentClassName);
        return $tab->add();
    }

    /**
     * Delete tab
     */
    protected function deleteTab($className) 
    {
        $id_tab = (int)Tab::getIdFromClassName($className);
        $allTableDeleted = true;
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $allTableDeleted = $tab->delete();
        } else {
            return false;
        }
        return $allTableDeleted;
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
		$cms_seo = new CMS(17, $this->context->language->id, $this->context->shop->id);
		if (Validate::isLoadedObject($cms_seo))
		  $this->smarty->assign('cms_seo', $cms_seo);
        return $this->display(__FILE__, 'views/templates/widget/surmesure.tpl');
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $notifications = false;
        if (Tools::isSubmit('submitSurmesure')) {
            if (!empty($this->context->controller->success)) {
                $notifications['messages'] = $this->context->controller->success;
                $notifications['nw_error'] = false;
            } elseif (!empty($this->context->controller->errors)) {
                $notifications['messages'] = $this->context->controller->errors;
                $notifications['nw_error'] = true;
            }
        }
        
        return [
            'notifications' => $notifications,
        ];
    }
    
    public function uploadCustomImage($name, $dir, $width = null, $height = null)
    {
        if (isset($_FILES[$name]['tmp_name']) && !empty($_FILES[$name]['tmp_name'])) {
            // Check image validity
            $max_size = isset($this->max_image_size) ? $this->max_image_size : 0;
            if ($error = ImageManager::validateUpload($_FILES[$name], Tools::getMaxUploadSize($max_size))) {
                $this->errors[] = $error;
            }

            $tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
            if (!$tmp_name) {
                return false;
            }

            if (!move_uploaded_file($_FILES[$name]['tmp_name'], $tmp_name)) {
                return false;
            }
            
            if (empty($this->errors)) {
                // Get filename and extension
                $ext = pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION);
                $filename = Tools::str2url(pathinfo($_FILES[$name]['name'], PATHINFO_FILENAME));
                while (file_exists($dir.$filename.'.'.$ext)) {
                    $parts = array();
                    if (preg_match('/^(.*)\-(\d)$/', $filename, $parts)) {
                        $filename = $parts[1] .'-'. ((int)$parts[2]+1);
                    } else {
                        $filename = $filename .'-1';
                    }
                }
            }
            
            // Evaluate the memory required to resize the image: if it's too much, you can't resize it.
            if (!ImageManager::checkImageMemoryLimit($tmp_name)) {
                $this->errors[] = $this->trans('Due to memory limit restrictions, this image cannot be loaded. Please increase your memory_limit value via your server\'s configuration settings. ', array(), 'Admin.Notifications.Error');
            }

            // Copy new image
            $is_resized = ImageManager::resize($tmp_name, $dir.$filename.'-hd.'.$ext, (int)$width, (int)$height, $ext)
                && ImageManager::resize($tmp_name, $dir.$filename.'.'.$ext, 280, null, $ext);
            if (empty($this->errors) 
                && !$is_resized
            ) {
                $this->errors[] = $this->trans('An error occurred while uploading the image.', array(), 'Admin.Notifications.Error');
            }

            if (count($this->errors)) {
                return false;
            }
            return $filename.'.'.$ext;
        }
        return true;
    }

    public function hookModuleRoutes()
    {
        $accentedCharacters = "àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ";
        return array(
            'module-surmesure-step1'   => array(
                'controller' => 'surmesure',
                'rule'       => 'tapis/step-{step}',
                'keywords'   => array(
                    'step' => array('regexp' => '[0-9]', 'param' => 'step'),
                ),
                'params'     => array(
                    'fc'     => 'module',
                    'module' => $this->name,
                ),
            ),
            'module-surmesure-step2'   => array(
                'controller' => 'surmesure',
                'rule'       => 'tapis/{brand}/{family}/step-{step}',
                'keywords'   => array(
                    'brand' => array('regexp' => '[A-Za-z0-9_ -'.$accentedCharacters.']+', 'param' => 'brand'),
                    'family' => array('regexp' => '[A-Za-z0-9_ -'.$accentedCharacters.']+', 'param' => 'family'),
                    'step' => array('regexp' => '[0-9]', 'param' => 'step'),
                ),
                'params'     => array(
                    'fc'     => 'module',
                    'module' => $this->name,
                ),
            ),
        );
    }
}

if (!function_exists("toUri")) {
	function toUri($text)
	{
		if (strpos($text, "]") !== false) {
			$text = substr($text, strrpos($text, "]") + 2);
		}
		return Tools::str2url($text);
	}
}
