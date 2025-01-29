<?php 

if (!defined('_PS_VERSION_')) {
    exit;
}

class Carmatselector extends Module
{
    public function __construct()
    {
        $this->name = 'carmatselector';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Nassim Haddad';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => '8.99.99',
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('CarMatSelector', [], 'Modules.Mymodule.Admin');
        $this->description = $this->trans('Description of CarMatSelector.', [], 'Modules.Mymodule.Admin');

        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.Mymodule.Admin');

        if (!Configuration::get('CARMATSELECTOR_NAME')) {
            $this->warning = $this->trans('No name provided', [], 'Modules.Mymodule.Admin');
        }
    }

    public function getContent()
    {
        $this->context->smarty->assign([
            'pathApp' => $this->getPathUri() . 'views/js/app.js',
            'chunkVendor' => $this->getPathUri() . 'views/js/chunk-vendors.js',
        ]);

        return $this->context->smarty->fetch('module:carmatselector/views/templates/admin/app.tpl');
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        return (
            parent::install() 
            && $this->installDb()
            && Configuration::updateValue('CARMATSELECTOR_NAME', 'CARMATSELECTOR')
        ); 
    }

    public function installDb()
    {
        return Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'carmatselector_attachment` (
			`id_carmatselector_attachment` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`name` VARCHAR(150) NOT NULL,
			`description` VARCHAR(250) NOT NULL,
			`active` TINYINT( 1 ) NOT NULL,
			INDEX (`id_carmatselector_attachment`)
		) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;') &&
            Db::getInstance()->execute('
			 CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'carmatselector_brand` (
			`id_carmatselector_brand` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`name` VARCHAR(150) NOT NULL,
			`active` TINYINT( 1 ) NOT NULL,
			INDEX (`id_carmatselector_brand`)
		) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;') &&
            Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'carmatselector_carbody` (
            `id_carmatselector_carbody` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(150) NOT NULL,
			`description` VARCHAR(250) NOT NULL,
            `active` TINYINT( 1 ) NOT NULL,
            INDEX (`id_carmatselector_carbody`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;') &&
            Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'carmatselector_carbody_configuration_assoc` (
            `id_carmatselector_carbody_configuration_assoc` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `id_carmatselector_carbody` INT NOT NULL,
            `id_carmatselector_configuration` INT NOT NULL,
            INDEX (`id_carmatselector_carbody_configuration_assoc`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;') &&
            Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'carmatselector_carbody_gamme_assoc` (
            `id_carmatselector_carbody_gamme_assoc` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `id_carmatselector_carbody` INT NOT NULL,
            `id_carmatselector_gamme` INT NOT NULL,
            INDEX (`id_carmatselector_carbody_gamme_assoc`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;') &&
            Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'carmatselector_color` (
            `id_carmatselector_color` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(50) NOT NULL,
            `hex_color` VARCHAR(10) NOT NULL,
            `image` VARCHAR(250) NOT NULL,
            `alias` INT NOT NULL,
            `active` TINYINT( 1 ) NOT NULL,
            INDEX (`id_carmatselector_color`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;') &&
            Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'carmatselector_color_gamme_assoc` (
            `id_carmatselector_color_gamme_assoc` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `id_carmatselector_color` INT NOT NULL,
            `id_carmatselector_gamme` INT NOT NULL,
            INDEX (`id_carmatselector_color_gamme_assoc`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;') &&
            Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'carmatselector_configuration` (
            `id_carmatselector_configuration` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(150) NOT NULL,
			`description` VARCHAR(250) NOT NULL,
            `active` TINYINT( 1 ) NOT NULL,
            INDEX (`id_carmatselector_configuration`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;') &&
            Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'carmatselector_gamme` (
            `id_carmatselector_gamme` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(150) NOT NULL,
            `rating` INT UNSIGNED NOT NULL,
            `opinion` VARCHAR(150) NOT NULL,
            `image` VARCHAR(150) NOT NULL,
            `alias` VARCHAR(150) NOT NULL,
            `active` TINYINT( 1 ) NOT NULL,
            INDEX (`id_carmatselector_gamme`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;') &&
            Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'carmatselector_model` (
            `id_carmatselector_model` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(150) NOT NULL,
            `id_carmatselector_brand` INT NOT NULL,
            `active` TINYINT( 1 ) NOT NULL,
            INDEX (`id_carmatselector_model`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;')  &&
            Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'carmatselector_product` (
            `id_carmatselector_product` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `id_carmatselector_brand` INT NOT NULL,
            `id_carmatselector_model` INT NOT NULL,
            `id_carmatselector_version` INT NOT NULL,
            `id_carmatselector_gamme` INT NOT NULL,
            `id_carmatselector_carbody` INT NOT NULL,
            `id_carmatselector_configuration` INT NOT NULL,
            `id_carmatselector_color` INT NOT NULL,
            `id_product_to_add` INT NOT NULL,
            INDEX (`id_carmatselector_product`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;') &&
            Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'carmatselector_version` (
            `id_carmatselector_version` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(150) NOT NULL,
            `id_carmatselector_model` UNSIGNED INT NOT NULL,
            `gabarit` VARCHAR(10) NOT NULL,
            `attachment` INT UNSIGNED NOT NULL,
            `carbody` INT UNSIGNED NOT NULL,
            `active` TINYINT( 1 ) NOT NULL,
            INDEX (`id_carmatselector_version`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;');
    }

    public function uninstall()
    {
        return (
            parent::uninstall() 
            && Configuration::deleteByName('CARMATSELECTOR_NAME')
        );
    }

}



